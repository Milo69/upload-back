<?php

header("Access-Control-Allow-Origin: *");


return [
  'url' => '/',
  'api' => [
    'basicAuth' => true,
    'allowInsecure' => false,
  ],
  'debug' => false,
  'kql' => [
    'auth' => false
  ],
  'panel' => [
    'file' => [
      'replace' => true
    ]
  ],
  'thumbs' => [
    'driver' => 'gd'
  ],
  'hooks' => [
    'file.replace:before' => function ($event) {
      // Ce hook se déclenche automatiquement lors du remplacement
      return true;
    },
    'file.create:after' => function ($file) {
      // Quand un nouveau fichier image est créé dans un événement
      if ($file->page()->intendedTemplate()->name() === 'evenement') {
        $page = $file->page();
        $currentImage = $page->image();
        
        // Si ce nouveau fichier n'est pas l'image actuelle de la page
        // alors supprimer les autres images de la page
        $pageImages = $page->images();
        if ($pageImages->count() > 1) {
          foreach ($pageImages as $img) {
            if ($img->id() !== $file->id()) {
              try {
                $img->delete();
              } catch (Exception $e) {
                // Ignorer les erreurs
              }
            }
          }
        }
      }
    }
  ]
];
