<?php

namespace netvod\theme;

class Theme
{
    public const ROOT_LIGHT = "src/style/themeLight.css";
    public const ROOT_DARK = "src/style/themeDark.css";
    public const ROOT_AUTO = "src/style/themeAuto.css";

    public static function getSrcStylesheet() : string
    {
        if (!isset($_SESSION['theme'])) return self::ROOT_AUTO;
        return match ($_SESSION['theme']) {
            "colorBackgroundChangeLight" => self::ROOT_LIGHT,
            "colorBackgroundChangeDark" => self::ROOT_DARK,
            default => self::ROOT_AUTO,
        };
    }

    public static function changeTheme() : void
    {
        //if ($isAuto) $_SESSION['theme'] = "";
        //else
        if ($_SESSION['theme'] == 'colorBackgroundChangeLight')
            $_SESSION['theme'] = 'colorBackgroundChangeDark';
        else $_SESSION['theme'] = 'colorBackgroundChangeLight';
    }
}