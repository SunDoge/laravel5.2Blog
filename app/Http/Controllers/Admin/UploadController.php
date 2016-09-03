<?php

namespace App\Http\Controllers\Admin;

use App\Services\UploadsManager;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use YuanChao\Editor\EndaEditor;
use zgldh\QiniuStorage\QiniuStorage;

class UploadController extends Controller
{
    protected $manager;

    public function __construct(UploadsManager $manager)
    {
        $this->manager = $manager;
    }

    public function index(Request $request)
    {
        $folder = $request->get('folder');
        $data = $this->manager->folderInfo($folder);

        return view('admin.upload.index', $data);
    }

    public function createFolder(Requests\UploadNewFolderRequest $request)
    {
        $new_folder = $request->get('new_folder');
        $folder = $request->get('folder') . '/' . $new_folder;

        $result = $this->manager->createDirectory($folder);

        if ($result === true) {
            return redirect()
                ->back()
                ->withSuccess("Folder '$new_folder' created.");
        }

        $error = $result ?: "An error occurred creating directory.";
        return redirect()
            ->back()
            ->withErrors([$error]);
    }

    public function deleteFile(Request $request)
    {
        $del_file = $request->get('del_file');
        $path = $request->get('folder') . '/' . $del_file;

        $result = $this->manager->deleteFile($path);

        if ($result === true) {
            return redirect()
                ->back()
                ->withSuccess("File '$del_file' deleted.");
        }

        $error = $result ?: "An error occurred deleting file.";
        return redirect()
            ->back()
            ->withErrors([$error]);
    }

    public function deleteFolder(Request $request)
    {
        $del_folder = $request->get('del_folder');
        $folder = $request->get('folder') . '/' . $del_folder;

        $result = $this->manager->deleteDirectory($folder);

        if ($result === true) {
            return redirect()
                ->back()
                ->withSuccess("Folder '$del_folder' deleted.");
        }

        $error = $result ?: "An error occurred deleting directory.";
        return redirect()
            ->back()
            ->withErrors([$error]);
    }

    public function uploadFile(Requests\UploadFileRequest $request)
    {
        $file = $_FILES['file'];
        $fileName = $request->get('file_name');
        $fileName = $fileName ?: $file['name'];
        $path = str_finish($request->get('folder'), '/') . $fileName;
        $content = File::get($file['tmp_name']);

        $result = $this->manager->saveFile($path, $content);

        if ($result === true) {
            return redirect()
                ->back()
                ->withSuccess("File '$fileName' uploaded.");
        }

        $error = $result ?: "An error occurred uploading file.";
        return redirect()
            ->back()
            ->withErrors([$error]);
    }

    public function uploadQiniu(Request $request)
    {
//        $data = EndaEditor::uploadImgFile('uploads/test');
//        return json_encode($data);

//        $file = $_FILES['file'];
//        $fileName = $request->get('file_name');
//        $fileName = $fileName ?: $file['name'];
//        $path = str_finish($request->get('folder'), '/') . $fileName;
//        $content = File::get($file['tmp_name']);
//
        $disk = QiniuStorage::disk('qiniu');
//        $result = $disk->put($fileName,$content);
//        return $result;


        try {
            // File Upload
            if ($request->hasFile('image')) {
                $pic = $request->file('image');
                if ($pic->isValid()) {
                    $newName = md5(rand(1, 1000) . $pic->getClientOriginalName()) . "." . $pic->getClientOriginalExtension();
//                    $pic->move($path, $newName);
//                    $url = asset($path . '/' . $newName);
                    $rename = $disk->put($newName, $pic);
                } else {
                    self::addError('The file is invalid');
                }
            } else {
                self::addError('Not File');
            }
        } catch (\Exception $e) {
            self::addError($e->getMessage());
        }

//        $url = $disk->downloadUrl($newName);
        $url = config('filesystems.disks.qiniu.domains.default') . $rename;
        $data = array(
            'status' => empty($message) ? 0 : 1,
            'message' => self::getLastError(),
            'url' => !empty($url) ? $url : ''
        );

        return $data;

    }

    protected static function getLastError()
    {
        return empty(self::$_errors) ? '' : array_pop(self::$_errors);
    }

    protected static function addError($message)
    {
        if (!empty($message)) {
            self::$_errors[] = $message;
        }
    }
}
