<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Log;
use App\Models\Doc;

class DocsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get all docs for the user
        $docs = Doc::where('user_id', auth()->user()->id)->get();
        return view('document', compact('docs'));
    }

    public function shared()
    {
        return view('shared');
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /*
     * Get all docs for the user
     * */
    public function getDocs(Request $request)
    {
        $docs = Doc::where('user_id', auth()->user()->id)->get();
        return response()->json([
            'message' => 'Documents retrieved successfully',
            'data' => $docs,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Add user_id to request
        $request->request->add(['user_id' => auth()->user()->id]);
        // Create the document
        $doc = Doc::create($request->all());
        return response()->json([
            'message' => 'Document created successfully',
            'data' => $doc,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Send back doc in json
        $doc = Doc::where('id', $id)->first();
        return response()->json([
            'message' => 'Document retrieved successfully',
            'data' => $doc,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Check if doc exists and if user owns it
        if (!Doc::where('id', $id)->exists()) {
            return response()->json([
                'message' => 'Document not found',
            ]);
        }
        if (Doc::where('id', $id)->first()->user_id !== auth()->user()->id) {
            return response()->json([
                'message' => 'You do not have permission to update this document',
            ]);
        }
        // todo - ^^^^ do this better, can just use Laravel auth check for this

        // Update the document
        Doc::where('id', $id)->update($request->all());
        // set status code to 204
        return response()->json([
            'message' => 'Document updated successfully',
        ], 204);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Delete the document
        $res = Doc::where('id', $id)->delete();
        return response()->json([
            'message' => 'Document deleted successfully',
            'res' => $res,
        ]);
    }

    /**
     *  Image uploader endpoint
     */
    public function uploadImage(Request $request) {
        $request->validate([
            'image' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);
        // $image_path = $request->file('image')->store('image', 'public');
        // dd($request->all());
        $imageName = time().'.'.$request->image->extension();
        $request->image->move(public_path('images'), $imageName);
        $url = public_path('images').'/'.$imageName;
        $data = array(
            'success' => 1,
            'file' => array(
                'url' => asset('/images/'.$imageName)
            )
        );
        return response()->json($data);        
    }

    /**
    *  File uploader endpoint
    */
    public function uploadFile(Request $request) {
        // $file = $request->file;
        // dd($request->file->getSize());
        $fileSize = $request->file->getSize();
        $fileExtension = $request->file->extension();
        $fileName = time().'.'.$fileExtension;
        $request->file->move(public_path('uploads'), $fileName);
        $url = public_path('uploads').'/'.$fileName;
        $data = array(
            'success' => 1,
            'file' => array(
                'name' => $fileExtension,
                'title' => $request->file->getClientOriginalName(),
                'size' => $fileSize,
                'url' => asset('/uploads/'.$fileName)
            )
        );
        return response()->json($data);        
    }

    /**
     *  endpoint for url data fetching
     */
    public function fetchUrl(Request $request) {
        // dd($request->url);
        // dd($request->all());
        $data = array(
            'success' => 1,
            'link' => $request->url,
            'meta' => array(
                'title' => 'Codex Team',
                'description' => 'Club of web-development, design and marketing. We build team learning how to build full-valued projects on the world market.',
                'image' => array(
                    'url' => "https://codex.so/public/app/img/meta_img.png"
                )
            )
        );
        return response()->json($data);        
    }
}
