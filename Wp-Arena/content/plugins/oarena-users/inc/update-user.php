<?php

class oArena_updateUser
{
    public function __construct()
    {

        add_action('init', [$this, 'updateUser']);
    }

    public function updateUser()
    {
        // If the form is submitted
        if (isset($_POST['update-member-submit'])) {

            // je stocke mes données dans des variables
            $user_name = ($_POST['user-name']);
            $user_email = ($_POST['user-email']);

            // If the input user-name is !empty 
            if (!empty($user_name)) {

                //Ajout d'un tableau contenant les erreurs
                $errorList = array();

                //Récupére l'utilisateur courant et met à jour le nom du user
                $current_user = wp_get_current_user();
                $current_user->nickname = esc_attr($user_name);
                // $current_user->user_login = esc_attr($user_name);
                $current_user->user_nicename = esc_attr($user_name);
                $current_user->display_name = esc_attr($user_name);
                $current_user->first_name = esc_attr($user_name);
                $current_user->last_name = esc_attr($user_name);
                $current_user->user_login = esc_attr($user_name);
                //var_dump($current_user);exit;
                
                wp_update_user($current_user);

                //var_dump($current_user);exit;
                //Met à jour le nom de l'utilisateur (celui avec lequel on se connecte)

                global $wpdb;
                $user_id = get_current_user_id();
                $wpdb->update($wpdb->users, array('user_login' => $user_name), array('ID' => $user_id));




                // global $current_user;
                // get_currentuserinfo ();
                // $current_username = $current_user->user_login;
                // global $wpdb;

                // $wpdb->query( "
                // UPDATE wp_users
                // SET user_login = $user_name
                // WHERE user_login = $current_username
                // " );
                // var_dump($current_username);exit;
                ///////////////////////////////

                //Mettre à jour le nom du membre

                // Récupére les noms des membres sous forme d'un tableau:

                global $wpdb; // On se connecte à la base de données du site
                // Requete qui récupére tout les post title ayant pour type members
                $members_name = $wpdb->get_results("
                SELECT `post_title` 
                FROM `wp_posts` 
                WHERE `post_type` ='members' 
                ;
                ");
                
                // Récupére les noms des membres en string et les stocke dans un tableau
                $member_list_name= Array();
                foreach ( $members_name  as $member_name ) {
                    $member_name->post_title;
                    array_push($member_list_name,$member_name->post_title);
                }
                
                //var_dump($member_list_name);exit;
                
                
                //TODO récupérer le nom du membre connecté
                
                $user_login = $current_user->user_login;
                //var_dump($user_login);exit;

                
                global $wpdb; // On se connecte à la base de données du site

                //Requete sql qui récupére l'ID du post ayant le nom du membre
                $member_id = $wpdb->get_results("
                SELECT ID
                FROM wp_posts
                WHERE `post_name` = '$user_login' AND `post_status`='publish'
                ;
                ");

                foreach($member_id as $id_member){
                $id_member->ID;
                }
                
                // var_dump($member_list_name);exit;

                // Si le nom nest pas pris
                // Update le post-title ayant pour identifiant id_member
                if (!in_array($user_name,($member_list_name))) {
                    // Update title_post
                      $my_post = array(
                        'ID'           => $id_member->ID,
                        'post_title'   => $user_name,
                    );

                    // Update the post into the database
                    wp_update_post( $my_post );
                    //var_dump($my_post);exit;
                } else $errorList[] = 'Ce nom est déjà pris';

            } 


            //Récupérer toutes les adresses mail des utilisateurs et admin du site

            global $wpdb; // On se connecte à la base de données du site
            
            $list_email_users = $wpdb->get_results("
                SELECT DISTINCT user_email
                FROM wp_users
                GROUP BY user_email
                ;
                ");

            foreach($list_email_users as $mail){
                $mail->user_email;
                array_push($list_email_users,$mail->user_email);
                }

            //var_dump($list_email_users);exit;


            //If the input user-email is !empty 
            if ((!empty($user_email)) & (!in_array($user_email,($list_email_users)))) {
                $current_user = wp_get_current_user();
                //$mail = the_author_meta( 'email', $current_user->ID );
                // Update email_post
                $current_user->user_email = esc_attr($user_email);
                wp_update_user($current_user);
                //var_dump($current_user);exit;
            } else if (!empty($user_email)) {
                $errorList[] = 'Cette adresse email est déjà prise!';
            } 

            // Si il y a des erreurs affiche les
            if (!empty($errorList)) {
                var_dump($errorList);exit;
            }

            // Redirige vers la page profil
                wp_redirect('profil');
                exit;
        } 
    }
}

    
        
 

        
  