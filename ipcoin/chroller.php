<?php

    use Facebook\WebDriver;

    require_once('../vendor/autoload.php');

    $host = "http://localhost:4444/wd/hub";

    $driver = Facebook\WebDriver\Remote\RemoteWebDriver::create($host, Facebook\WebDriver\Remote\DesiredCapabilities::chrome("./chromedriver.exe"));

    // IDとPWを含めて、Request
    $result = $driver->get("https://www.google.com");

    // フィルタリングのメインカテゴリーをクリック
    $driver->FindElement(WebDriver\WebDriverBy::xpath('/html/body/div[1]/div[3]/form/div[1]/div[1]/div[1]/div/div[2]/input'))->click();


    // keyboard操作
    $driver->getKeyboard()->sendKeys('Selenium');          // 検索フォームに検索語を入力
    $driver->getKeyboard()->pressKey("\xEE\x80\x87"); // ENTERを入力

    $driver->FindElement(WebDriver\WebDriverBy::xpath('//*[@id="kp-wp-tab-overview"]/div[1]/div/div/div/div/div/div[1]/div/div/div/span[2]/a'))->click();

    // idがmain_catである要素を全部読み込む
    $text = $driver->FindElements(WebDriver\WebDriverBy::linkText('<br>Selenium'));

    sleep(5);

    $driver->quit();
?>