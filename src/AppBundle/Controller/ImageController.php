<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ImageController extends Controller
{
    public function updateImageAction(Request $request)
    {
        sleep(5);
        
        $data = $request->request->all();
        $this->get('app.repository.image')->updateImage($data);

        return new JsonResponse(['status' => 'OK']);
    }
}
