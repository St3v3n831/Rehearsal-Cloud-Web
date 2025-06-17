<?php
   // Determine which view to display based on the 'view' query parameter
   $view = isset($_GET['view']) ? $_GET['view'] : 'login';

   // Define the path to the views
   $viewPath = './view/';

   // Map valid views to their corresponding files
   $validViews = [
       'login' => 'login.html',
       'signup' => 'signup.html'
   ];

   // Check if the requested view is valid; default to login if not
   if (!array_key_exists($view, $validViews)) {
       $view = 'login';
   }

   // Include the selected view
   $viewFile = $viewPath . $validViews[$view];
   if (file_exists($viewFile)) {
       include $viewFile;
   } else {
       http_response_code(404);
       echo "Error: The file '$viewFile' does not exist. Please ensure 'views/login.html' and 'views/signup.html' are in the correct directory.";
       exit;
   }
   ?>