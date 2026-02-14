<?php

namespace App\Http\Controllers;

use App\Models\newpost_details;
use App\Services\ImageProcessingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Admin extends Controller
{
    public function __construct(private ImageProcessingService $imgService)
    {
        $this->middleware('auth');
        $this->middleware('web');
    }

    public function index(Request $request)
    {

        $news_arr = DB::table('newpost_details')->get();
        $message = '';
        if ($request->session()->get('status') !== 'active') {
            $request->session()->flash('message', 'Hello Admin Welcome here');
            $request->session()->flash('status', 'active');
            $request->session()->put('message_shown', false);
        }

        if ($request->session()->get('status') === 'active' && !$request->session()->get('message_shown')) {
            if ($request->session()->has('message')) {
                $message = $request->session()->get('message');
                $request->session()->forget('message');
                $request->session()->put('message_shown', true);
            }
        }
        $request->session()->put('name', 'Suraj');
        $data = $request->session()->all();
        return view('admin/adminpanel', ['details_arr' => $news_arr], compact('message'));
    }

    public function addpost()
    {
        return view('admin/addPost');
    }

    public function save_post(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|max:255',
                'desc' => 'required',
                'file' => 'required|file|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/webp|max:5120',
                'category' => 'nullable|string',
                'tags' => 'nullable|array',
                'tags.*' => 'string|max:50',
                'featured' => 'boolean',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'slug' => 'nullable|string|max:255|unique:newpost_details,slug',
                'table_of_contents' => 'nullable|json',
                'reading_time' => 'nullable|integer',
                'word_count' => 'nullable|integer',

            ]);
            if (empty($validated['table_of_contents'])) {
                $validated['table_of_contents'] = $this->generateTOC($validated['desc']);
            }

            if (empty($validated['reading_time'])) {
                $wordCount = str_word_count(strip_tags($validated['desc']));
                $validated['word_count'] = $wordCount;
                $validated['reading_time'] = ceil($wordCount / 200);
            }

            $data = [
                'title' => $validated['title'],
                'description' => $validated['desc'],
                'created_date' => now(),
                'category' => $validated['category'] ?? null,
                'slug' => $validated['slug'] ?? null,
                'meta_title' => $validated['meta_title'] ?? null,
                'tags' => json_encode($validated['tags']) ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
                'table_of_contents' => $validated['table_of_contents'],
                'reading_time' => $validated['reading_time'],
                'word_count' => $validated['word_count'],
                'active' => 1,
                'is_featured' => $validated['featured'] ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            

            if ($request->hasFile('file')) {

                $file = $request->file('file');

                if (!$file->isValid()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Uploaded file is corrupted or invalid.'
                    ], 422);
                }

                $processed = $this->imgService->processImage($file->getPathname(), [
                    'quality' => 80,
                    'formats' => ['jpeg', 'webp'],
                    'sizes' => ['thumb', 'medium', 'large', 'original']
                ]);
                $storagePaths = [];
                foreach ($processed as $size => $formats) {
                    foreach ($formats as $format => $path) {
                        $storagePaths[$size][$format] = $this->imgService->saveToStorage($path, 'uploads');
                    }
                }
                // $fileName = time() . '_' . $file->getClientOriginalName();
                // $destination = storage_path('app/public/uploads/' . $fileName);
                // copy($compressedPath, $destination);
                $data['file_path'] = json_encode($storagePaths);
            } else {
                $data['file_path'] = null;
            }
            $inserted = DB::table('newpost_details')->insert($data);

            if (!$inserted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Database error: Failed to save post.'
                ], 500);
            }
            return response()->json([
                'success' => true,
                'message' => 'Post created successfully!',
                'reset_form' => true,
                'redirect' => '/admin'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error occurred.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unexpected error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function view_editPage($id)
    {
        $post = DB::table('newpost_details')->where('id', $id)->firstOrFail();
        // echo "<pre>";
        // print_r($post);
        // echo "</pre>";
        // return ;
        // $item = DB::findOrFail($id);
        return view('admin.editpage', compact('post'));
    }

    public function update_data(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|max:255',
                'desc' => 'required',
                'file' => 'required|file|mimetypes:image/jpeg,image/png,image/jpg,image/gif,image/webp|max:5120',
                'category' => 'nullable|string',
                'tags' => 'nullable|array',
                'tags.*' => 'string|max:50',
                'featured' => 'boolean',
                'meta_title' => 'nullable|string|max:255',
                'meta_description' => 'nullable|string|max:500',
                'slug' => 'nullable|string|max:255',

            ]);
            
            $isSlugExists = newpost_details::checkSlugExists($validated['slug'], $id);
            if ($isSlugExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'The slug has already been taken.'
                ], 422);
            }

            $data = [
                'title' => $validated['title'],
                'description' => $validated['desc'],
                'category' => $validated['category'] ?? null,
                'slug' => $validated['slug'] ?? null,
                'meta_title' => $validated['meta_title'] ?? null,
                'tags' => json_encode($validated['tags']) ?? null,
                'meta_description' => $validated['meta_description'] ?? null,
                'is_featured' => $validated['featured'] ?? 0,
                'updated_at' => now(),
            ];
            $post = DB::table('newpost_details')
                ->select('file_path')
                ->where('id', $id)
                ->first();

            if ($request->hasFile('file')) {

                $file = $request->file('file');

                if (!$file->isValid()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Uploaded file is corrupted or invalid.'
                    ], 422);
                }

                $processed = $this->imgService->processImage($file->getPathname(), [
                    'quality' => 80,
                    'formats' => ['jpeg', 'webp'],
                    'sizes' => ['thumb', 'medium', 'large', 'original']
                ]);
                $storagePaths = [];
                foreach ($processed as $size => $formats) {
                    foreach ($formats as $format => $path) {
                        $storagePaths[$size][$format] = $this->imgService->saveToStorage($path, 'uploads');
                    }
                }
                $data['file_path'] = json_encode($storagePaths);
            } else {
                if (!empty($post->file_path)) {
                    $data['file_path'] = json_encode([$post->file_path]);
                } else {
                    $data['file_path'] = null;
                }
            }

            $affected = DB::table('newpost_details')
                ->where('id', $id)
                ->update($data);

            if (!$affected) {
                return response()->json([
                    'success' => false,
                    'message' => 'Database error: Failed to update post.'
                ], 500);
            }
            return response()->json([
                'success' => true,
                'message' => 'Post Update successfully!',
                'reset_form' => true,
                'redirect' => '/admin'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error occurred.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unexpected error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function delete_data($id)
    {
        $deleted = DB::table('newpost_details')->where('id', $id)->delete();

        if ($deleted) {
            return response()->json(['success' => 'Record deleted successfully']);
        } else {
            return response()->json(['error' => 'Error while deleting the record'], 500);
        }
    }

    private function generateTOC($content)
    {
        $headings = [];
        $lines = explode("\n", $content);

        foreach ($lines as $index => $line) {
            $line = trim($line);

            if (preg_match('/^(#{2,4})\s+(.+)$/', $line, $matches)) {
                $level = strlen($matches[1]);
                $title = trim($matches[2]);

                $headings[] = [
                    'id' => 'section-' . (count($headings) + 1),
                    'title' => $title,
                    'level' => $level,
                    'slug' => Str::slug($title),
                    'order' => count($headings) + 1
                ];
            }
        }

        return json_encode($headings);
    }
}
