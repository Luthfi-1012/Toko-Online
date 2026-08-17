<?php

namespace App\Helpers;

class ImageHelper {
    public static function uploadAndResize($file, $directory = 'public/storage/img-user/image', $fileName, $width = null, $height = null) {
        error_log('Masuk ke fungsi uploadAndResize');
        
        $destinationPath = public_path($directory); // Gunakan direktori yang benar
        error_log('Direktori tujuan: ' . $destinationPath);
        
        $extension = strtolower($file->getClientOriginalExtension());
        error_log('Ekstensi file: ' . $extension);

        if (!in_array($extension, ['jpeg', 'jpg', 'png', 'gif'])) {
            throw new \Exception('Ekstensi file tidak didukung');
        }

        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0755, true);
        }

        if (!$file->isValid()) {
            throw new \Exception('File tidak valid');
        }

        $image = null;

        // Tentukan metode pembuatan gambar berdasarkan ekstensi file
        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                $image = imagecreatefromjpeg($file->getRealPath());
                break;
            case 'png':
                $image = imagecreatefrompng($file->getRealPath());
                break;
            case 'gif':
                $image = imagecreatefromgif($file->getRealPath());
                break;
        }

        // Resize gambar jika lebar diset
        if ($width) {
            $oldWidth = imagesx($image);
            $oldHeight = imagesy($image);
            $aspectRatio = $oldWidth / $oldHeight;
            if (!$height) {
                $height = $width / $aspectRatio; // Hitung tinggi dengan mempertahankan aspek rasio
            }
            $newImage = imagecreatetruecolor($width, $height);
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $width, $height, $oldWidth, $oldHeight);
            imagedestroy($image);
            $image = $newImage;
        }

        // Simpan gambar dengan kualitas asli
        switch ($extension) {
            case 'jpeg':
            case 'jpg':
                imagejpeg($image, $destinationPath . '/' . $fileName);
                break;
            case 'png':
                imagepng($image, $destinationPath . '/' . $fileName);
                break;
            case 'gif':
                imagegif($image, $destinationPath . '/' . $fileName);
                break;
        }

        imagedestroy($image);
        error_log('Gambar berhasil disimpan: ' . $destinationPath . '/' . $fileName);
        
        return $fileName;
    }
}
