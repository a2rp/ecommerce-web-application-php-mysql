<?php
function storeUploadedImage($file, $destinationDirectory) {
    if (!is_array($file) || !isset($file["error"]) || $file["error"] === UPLOAD_ERR_NO_FILE) {
        return null;
    }

    if ($file["error"] !== UPLOAD_ERR_OK || !isset($file["tmp_name"], $file["size"]) || $file["size"] > 5 * 1024 * 1024 || !is_uploaded_file($file["tmp_name"])) {
        return false;
    }

    $imageTypes = [
        "image/jpeg" => "jpg",
        "image/png" => "png",
        "image/webp" => "webp",
        "image/gif" => "gif",
    ];
    $fileInfo = new finfo(FILEINFO_MIME_TYPE);
    $mimeType = $fileInfo->file($file["tmp_name"]);
    $imageInfo = getimagesize($file["tmp_name"]);
    if (!isset($imageTypes[$mimeType]) || $imageInfo === false || $imageInfo["mime"] !== $mimeType) {
        return false;
    }

    $fileName = bin2hex(random_bytes(16)) . "." . $imageTypes[$mimeType];
    $destination = rtrim($destinationDirectory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $fileName;
    if (!move_uploaded_file($file["tmp_name"], $destination)) {
        return false;
    }

    return $fileName;
}
?>
