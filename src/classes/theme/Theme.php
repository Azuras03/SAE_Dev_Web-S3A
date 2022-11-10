<?php

namespace netvod\theme;

class Theme
{
    public const ROOT_LIGHT = "src/style/themeLight.css";
    public const ROOT_DARK = "src/style/themeDark.css";
    public const ROOT_AUTO = "src/style/themeAuto.css";
    public const LIGHT = "colorBackgroundChangeLight";
    public const DARK = "colorBackgroundChangeDark";
    public const AUTO = "";

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
        //if (!isset($_SESSION['autoTheme']) || $_SESSION['autoTheme']) $_SESSION['autoTheme'] = false;

        if ($_SESSION['theme'] == self::LIGHT)
            $_SESSION['theme'] = self::DARK;
        else $_SESSION['theme'] = self::LIGHT;
    }

    public static function switchAutoTheme() : void
    {
        $_SESSION['theme'] = self::AUTO;
    }
}