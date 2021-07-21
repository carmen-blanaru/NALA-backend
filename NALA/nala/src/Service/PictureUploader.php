<?php

namespace App\Service;

use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class PictureUploader
{
    private $slugger;
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * this method will help us to upload pictures for the avatar
     * the image is moved from the form type to a folder called "uploads"
     *
     * @param Form $form
     * @param string $pictureName
     * @param [type] $uploadFolder
     *
     */
    public function upload(Form $form, string $pictureName, $uploadFolder = null)
    {   // if $uploadFolder is not defined,
        //use the global variable $_ENV UPLOAD_FOLDER called into the .env file
        if ($uploadFolder === null) {
            $uploadFolder = $_ENV['UPLOAD_FOLDER'];
        }

        // the real file is recovered
        $pictureFile = $form->get($pictureName)->getData();

        // if there is an image to upload, it will be sent to the uploads folder
        if ($pictureFile) {
            $originalFileName = pathinfo($pictureFile->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFileName = $this->slugger->slug($originalFileName);

            // after verification, the image has a new name with an uniq id
            $newFileName = $safeFileName . '-' . uniqid() . '.' . $pictureFile->guessExtension();

            try {
                // if till here everything in alright, now the entity in uploaded
                $pictureFile->move($uploadFolder, $newFileName);

                // A the end, we have the the picture's name
                return $newFileName;
            } catch (FileException $e) {
                // if non, message error displayed
            }
            return null;
        }
    }
}