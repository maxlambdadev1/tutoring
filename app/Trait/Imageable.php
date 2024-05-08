<?php
namespace App\Helper;

Trait Imageable 
{
    public function storeProfileImage($request)
    {
        
        $path = public_path($this->profile_path);

        if ( ! file_exists($path) ) {
            mkdir($path, 0777, true);
        }

        $file = $request->file('photo');

        $fileName = $this->id . '_' . uniqid() . '_' . trim($file->getClientOriginalName());
        
        $this->photo = $this->profile_path . '/' . $fileName;
        $this->save();
        
        $file->move($path, $fileName);

        return $this;
    }
}