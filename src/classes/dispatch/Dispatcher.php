<?php

namespace netvod\dispatch;

use netvod\action\ActionDisplayListProgress;

class Dispatcher
{

    public static string $themeChangesStyle = "";
    public ?string $action;

    public function __construct()
    {
        $this->action = $_GET['action'] ?? null;
    }

    public function run(): string
    {
        $affichage2 = "";
        //Affichage du contenu
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case "signin" :
                    $action = new \netvod\action\ActionSignIn();
                    $affichage2 .= $action->execute();
                    break;
                case "userinfos" :
                    $action = new \netvod\action\ActionUserInfos();
                    $affichage2 .= $action->execute();
                    break;
                case "signup" :
                    $action = new \netvod\action\ActionSignUp();
                    $affichage2 .= $action->execute();
                    break;
                case "display-episode" :
                    $action = new \netvod\action\ActionDisplayEpisode();
                    $affichage2 .= $action->execute();
                    break;
                case "display-serie" :
                    $action = new \netvod\action\ActionDisplaySerie();
                    $affichage2 .= $action->execute();
                    break;
                case "saveseriefav" :
                    $action = new \netvod\action\ActionSaveSeriePref();
                    $affichage2 .= $action->execute();
                    break;
                case "showfavserie" :
                    $action = new \netvod\action\ActionDisplayFavoriteSeries();
                    $affichage2 .= $action->execute();
                    break;
                case "progress-list" :
                    $action = new ActionDisplayListProgress();
                    $affichage2 .= $action->execute();
                    break;
                case 'review-list':
                    $action = new \netvod\action\ActionDisplayReviews();
                    $affichage2 .= $action->execute();
                    break;
                case "chgtheme" :
                    $action = new \netvod\action\ActionChangeTheme();
                    $affichage2 .= $action->execute();
                    break;
                case "signout" :
                    $action = new \netvod\action\ActionSignOut();
                    $affichage2 .= $action->execute();
                    break;
                case"activateAccount":
                    $action = new \netvod\action\ActionActivateAccount();
                    $affichage2 .= $action->execute();
                    break;
                case "emailpasswordreset":
                    $action = new \netvod\action\ActionEmailPasswordReset();
                    $affichage2 .= $action->execute();
                    break;
                    case "passwordreset":
                    $action = new \netvod\action\ActionPasswordReset();
                    $affichage2 .= $action->execute();
            }
        } else {
            $action = new \netvod\action\ActionDisplaySerie();
            $affichage2 .= $action->execute();
        }
        return $affichage2;

    }

    /**
     * Method that return string corresponding to the main content to show to user
     * @param string $html
     * @return void
     */
    private function renderPage(string $html): void
    {
        echo $html;
    }
}