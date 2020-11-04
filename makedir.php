<?php

 $uploaddir = './uploads/lead_documents/001test/';
                    if (!is_dir($uploaddir) && !mkdir($uploaddir)) {
                        die("Error creating folder $uploaddir");
                    }
					
?>