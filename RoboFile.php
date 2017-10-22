<?php
/**
 * This is project's console commands configuration for Robo task runner.
 *
 * Download robo.phar from http://robo.li/robo.phar and type in the root of the repo: $ php robo.phar
 * Or do: $ composer update, and afterwards you will be able to execute robo like $ php vendor/bin/robo
 *
 * @see  http://robo.li/
 */
require_once 'vendor/autoload.php';

/**
 * Class RoboFile
 *
 * @since  1.6
 */
class RoboFile extends \Robo\Tasks
{
    // Load tasks from composer, see composer.json
    use Joomla\Testing\Robo\Tasks\LoadTasks;

    /**
     * File extension for executables
     *
     * @var string
     */
    private $executableExtension = '';

    /**
     * Local configuration parameters
     *
     * @var array
     */
    private $configuration = array();

    /**
     * Path to the local CMS root
     *
     * @var string
     */
    private $cmsPath = '';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->cmsPath = $this->getCmsPath();
        // Set default timezone (so no warnings are generated if it is not set)
        date_default_timezone_set('UTC');
    }


    /**
     * Downloads and prepares a Joomla CMS site for testing
     *
     * @param   int   $use_htaccess     (1/0) Rename and enable embedded Joomla .htaccess file
     * @param   bool  $addCertificates  Optional.  Adds the certificates to the Joomla-provided PEM file
     *
     * @return mixed
     */

    /**
     * Executes Selenium System Tests in your machine
     *
     * @param   array  $opts  Use -h to see available options
     *
     * @return mixed
     */
    public function runTest($opts = [
        'test|t'	    => null,
        'suite|s'	    => 'acceptance'])
    {
        $this->getComposer();

        $this->taskComposerInstall()->run();

        if (isset($opts['suite'])
            && ('api' === $opts['suite'] || 'apisoap' === $opts['suite']))
        {
            // Do not launch selenium when running API tests
        }
        else
        {
            if (!$this->isWindows())
            {
                $this->taskSeleniumStandaloneServer()
                    ->setURL("http://localhost:4444")
                    ->runSelenium()
                    ->waitForSelenium()
                    ->run()
                    ->stopOnFail();
            }
            else
            {
                $this->taskSeleniumStandaloneServer()
                    ->setURL("http://localhost:4444")
                    ->runSelenium()
                    ->stopOnFail();
            }
        }

        // Make sure to Run the Build Command to Generate AcceptanceTester
        $this->_exec("vendor/bin/codecept build");

//        if (!$opts['test'])
//        {
//            $this->say('Available tests in the system:');
//
//            $iterator = new RecursiveIteratorIterator(
//                new RecursiveDirectoryIterator(
//                    $opts['suite'],
//                    RecursiveDirectoryIterator::SKIP_DOTS
//                ),
//                RecursiveIteratorIterator::SELF_FIRST
//            );
//
//            $tests = array();
//
//            $iterator->rewind();
//            $i = 1;
//
//            while ($iterator->valid())
//            {
//                if (strripos($iterator->getSubPathName(), 'cept.php')
//                    || strripos($iterator->getSubPathName(), 'cest.php')
//                    || strripos($iterator->getSubPathName(), '.feature'))
//                {
//                    $this->say('[' . $i . '] ' . $iterator->getSubPathName());
//                    $tests[$i] = $iterator->getSubPathName();
//                    $i++;
//                }
//
//                $iterator->next();
//            }
//
//            $this->say('');
//            $testNumber	= $this->ask('Type the number of the test  in the list that you want to run...');
//            $opts['test'] = $tests[$testNumber];
//        }

        $pathToTestFile = './' . $opts['suite'] . '/' . $opts['test'];

        // If test is Cest, give the option to execute individual methods
//        if (strripos($opts['test'], 'cest'))
//        {
//            // Loading the class to display the methods in the class
//            require './' . $opts['suite'] . '/' . $opts['test'];
//
//            $classes = Nette\Reflection\AnnotationsParser::parsePhp(file_get_contents($pathToTestFile));
//            $className = array_keys($classes)[0];
//
//            if (strripos($className, 'cest'))
//            {
//                $testFile = new Nette\Reflection\ClassType($className);
//                $testMethods = $testFile->getMethods(ReflectionMethod::IS_PUBLIC);
//
//                foreach ($testMethods as $key => $method)
//                {
//                    $this->say('[' . $key . '] ' . $method->name);
//                }
//
//                $this->say('');
//                $methodNumber = $this->askDefault('Choose the method in the test to run (hit ENTER for All)', 'All');
//
//                if ($methodNumber != 'All')
//                {
//                    $method = $testMethods[$methodNumber]->name;
//                    $pathToTestFile = $pathToTestFile . ':' . $method;
//                }
//            }
//        }

//        $this->taskCodecept()
//            ->test($pathToTestFile)
//            ->arg('--steps')
//            ->arg('--debug')
//            ->arg('--fail-fast')
//            ->run()
//            ->stopOnFail();

        $this->taskCodecept()
            ->arg('--steps')
            //  ->arg('--debug')
            ->arg('--tap')
            ->arg('--fail-fast')
            ->arg('tests/acceptance/AdminLoginCest.php')
            ->run();

        $this->killSelenium();
//        if (!'api' == $opts['suite'])
//        {
//            $this->killSelenium();
//        }
    }

//    /**
//     * Preparation for running manual tests after installing Joomla/Extension and some basic configuration
//     *
//     * @param   int     $use_htaccess     (1/0) Rename and enable embedded Joomla .htaccess file
//     * @param   string  $package_method   Optional. Availabe methods are "phing" or "gulp". "gulp" by default
//     * @param   bool    $addCertificates  Optional.  Adds the certificates to the Joomla-provided PEM file
//     *
//     * @return void
//     */
//    public function runTestPreparation($use_htaccess = 0, $package_method = 'gulp', $addCertificates = true)
//    {
//        $this->prepareSiteForSystemTests($use_htaccess, $addCertificates);
//
//        $this->getComposer();
//
//        $this->taskComposerInstall()->run();
//
//        $this->prepareReleasePackages($package_method);
//
//        if (!$this->isWindows())
//        {
//            $this->taskSeleniumStandaloneServer()
//                ->setURL("http://localhost:4444")
//                ->runSelenium()
//                ->waitForSelenium()
//                ->run()
//                ->stopOnFail();
//        }
//        else
//        {
//            $this->taskSeleniumStandaloneServer()
//                ->setURL("http://localhost:4444")
//                ->runSelenium()
//                ->stopOnFail();
//        }
//
//        // Make sure to Run the Build Command to Generate AcceptanceTester
//        $this->_exec("vendor/bin/codecept build");
//        $this->taskCodecept()
//            ->arg('--steps')
//            //  ->arg('--debug')
//            ->arg('--tap')
//            ->arg('--fail-fast')
//            ->arg('tests/acceptance/integration/MassDiscountCheckoutCest.php')
//            ->run()
//            ->stopOnFail();
//        $this->killSelenium();
//
//    }
//
//    /**
//     * Updates the redSHOPB2B in the JoomlaCMS testing installation for manual tests
//     *
//     * @param   int     $use_htaccess    (1/0) Rename and enable embedded Joomla .htaccess file
//     * @param   string  $package_method  Optional. Availabe methods are "phing" or "gulp". "gulp" by default
//     *
//     * @return void
//     */
//    public function runTestUpdate($use_htaccess = 0, $package_method = 'gulp')
//    {
//        $this->prepareReleasePackages($package_method);
//
//        if (!$this->isWindows())
//        {
//            $this->taskSeleniumStandaloneServer()
//                ->setURL("http://localhost:4444")
//                ->runSelenium()
//                ->waitForSelenium()
//                ->run()
//                ->stopOnFail();
//        }
//        else
//        {
//            $this->taskSeleniumStandaloneServer()
//                ->setURL("http://localhost:4444")
//                ->runSelenium()
//                ->stopOnFail();
//        }
//
//        $this->_exec("vendor/bin/codecept build");
//        $this->taskCodecept()
//            ->arg('--steps')
//            //  ->arg('--debug')
//            ->arg('--tap')
//            ->arg('--fail-fast')
//            ->arg('tests/acceptance/integration/MassDiscountCheckoutCest.php')
//            ->run()
//            ->stopOnFail();
//        $this->killSelenium();
//    }
//
//    public function runTestsEnv($use_htaccess = 0, $env = 'bootstrap2', $package_method = 'gulp', $database_host = null, $addCertificates = true)
//    {
//        $this->prepareSiteForSystemTests($use_htaccess, $addCertificates);
//
//        $this->getComposer();
//
//        $this->taskComposerInstall()->run();
//
//        $this->prepareReleasePackages($package_method);
//
//        if (!$this->isWindows())
//        {
//            $this->taskSeleniumStandaloneServer()
//                ->setURL("http://localhost:4444")
//                ->runSelenium()
//                ->waitForSelenium()
//                ->run()
//                ->stopOnFail();
//        }
//        else
//        {
//            $this->taskSeleniumStandaloneServer()
//                ->setURL("http://localhost:4444")
//                ->runSelenium()
//                ->stopOnFail();
//        }
//
//        $this->_exec("vendor/bin/codecept build");
//        $this->taskCodecept()
//            ->arg('--steps')
//            //  ->arg('--debug')
//            ->arg('--tap')
//            ->arg('--fail-fast')
//            ->arg('tests/acceptance/integration/MassDiscountCheckoutCest.php')
//            ->run()
//            ->stopOnFail();
//        $this->killSelenium();
//
//
//        $this->killSelenium();
//    }
//    /**
//     * Function to Run tests in a Group
//     *
//     * @param   int     $use_htaccess     (1/0) Rename and enable embedded Joomla .htaccess file
//     * @param   string  $package_method   Optional. Availabe methods are "phing" or "gulp". "gulp" by default
//     * @param   string  $database_host    Optional. If using Joomla Vagrant Box do: $ vendor/bin/robo 0 gulp run:tests 33.33.33.58
//     * @param   bool    $addCertificates  Optional.  Adds the certificates to the Joomla-provided PEM file
//     *
//     * @return void
//     */
//    public function runTests($use_htaccess = 0, $package_method = 'gulp', $database_host = null, $addCertificates = true)
//    {
//        $this->prepareSiteForSystemTests($use_htaccess, $addCertificates);
//
//        $this->getComposer();
//
//        $this->taskComposerInstall()->run();
//
//        $this->prepareReleasePackages($package_method);
//
//        if (!$this->isWindows())
//        {
//            $this->taskSeleniumStandaloneServer()
//                ->setURL("http://localhost:4444")
//                ->runSelenium()
//                ->waitForSelenium()
//                ->run()
//                ->stopOnFail();
//        }
//        else
//        {
//            $this->taskSeleniumStandaloneServer()
//                ->setURL("http://localhost:4444")
//                ->runSelenium()
//                ->stopOnFail();
//        }
//
//        // Make sure to Run the Build Command to Generate AcceptanceTester
//        $this->_exec("vendor/bin/codecept build");
//        $this->taskCodecept()
//            ->arg('--steps')
//            //  ->arg('--debug')
//            ->arg('--tap')
//            ->arg('--fail-fast')
//            ->arg('tests/acceptance/integration/MassDiscountCheckoutCest.php')
//            ->run()
//            ->stopOnFail();
//        $this->killSelenium();
//    }
//
//    /**
//     * Stops Selenium Standalone Server
//     *
//     * @return void
//     */
//    public function killSelenium()
//    {
//        $this->_exec('curl http://localhost:4444/selenium-server/driver/?cmd=shutDownSeleniumServer');
//    }
//
//    /**
//     * Downloads Composer
//     *
//     * @return void
//     */
//    private function getComposer()
//    {
//        // Make sure we have Composer
//        if (!file_exists('./composer.phar'))
//        {
//            $this->_exec('curl --retry 3 --retry-delay 5 -sS https://getcomposer.org/installer | php');
//        }
//    }
//
//    /**
//     * Runs Selenium Standalone Server.
//     *
//     * @return void
//     */
//    public function runSelenium()
//    {
//        $this->_exec("vendor/bin/selenium-server-standalone -debug >> selenium.log 2>&1 &");
//    }
//
//    public function sendScreenshotFromTravisToGithub($cloudName, $apiKey, $apiSecret, $GithubToken, $repoOwner, $repo, $pull)
//    {
//        $errorSelenium = true;
//        $reportError = false;
//        $reportFile = 'selenium.log';
//        $body = 'Selenium log:' . chr(10) . chr(10);
//
//        // Loop throught Codeception snapshots
//        if (file_exists('_output') && $handler = opendir('_output'))
//        {
//            $reportFile = '_output/report.tap.log';
//            $body = 'Codeception tap log:' . chr(10) . chr(10);
//            $errorSelenium = false;
//        }
//
//        if (file_exists($reportFile))
//        {
//            if ($reportFile)
//            {
//                $body .= file_get_contents($reportFile, null, null, 15);
//            }
//
//            if (!$errorSelenium)
//            {
//                $handler = opendir('_output');
//
//                while (false !== ($errorSnapshot = readdir($handler)))
//                {
//                    // Avoid sending system files or html files
//                    if (!('png' === pathinfo($errorSnapshot, PATHINFO_EXTENSION)))
//                    {
//                        continue;
//                    }
//
//                    $reportError = true;
//                    $this->say("Uploading screenshots: $errorSnapshot");
//
//                    Cloudinary::config(
//                        array(
//                            'cloud_name' => $cloudName,
//                            'api_key'    => $apiKey,
//                            'api_secret' => $apiSecret
//                        )
//                    );
//
//                    $result = \Cloudinary\Uploader::upload(realpath(dirname(__FILE__) . '/_output/' . $errorSnapshot));
//                    $this->say($errorSnapshot . 'Image sent');
//                    $body .= '![Screenshot](' . $result['secure_url'] . ')';
//                }
//            }
//
//            // If it's a Selenium error log, it prints it in the regular output
//            if ($errorSelenium)
//            {
//                $this->say($body);
//            }
//
//            // If it needs to, it creates the error log in a Github comment
//            if ($reportError)
//            {
//                $this->say('Creating Github issue');
//                $client = new \Github\Client;
//                $client->authenticate($GithubToken, \Github\Client::AUTH_HTTP_TOKEN);
//                $client
//                    ->api('issue')
//                    ->comments()->create(
//                        $repoOwner, $repo, $pull,
//                        array(
//                            'body'  => $body
//                        )
//                    );
//            }
//        }
//    }
//
//    private function travisGetBaseBranchFromPullRequest()
//    {
//        if (!getenv('TRAVIS_PULL_REQUEST'))
//        {
//            return false;
//        }
//
//        $githubToken = getenv('GITHUB_TOKEN');
//        $repoOwner = getenv('ORGANIZATION');
//        $repo = getenv('REPO');
//        $pull = getenv('TRAVIS_PULL_REQUEST');
//
//        $client = new \Github\Client;
//        $client->authenticate($githubToken, \Github\Client::AUTH_HTTP_TOKEN);
//        $pullRequest = $client->api('pullRequest')
//            ->show(
//                $repoOwner, $repo, $pull
//            );
//
//        return $pullRequest['base']['ref'];
//    }
//
//    private function getBaseBranch()
//    {
//        $baseBranch = $this->travisGetBaseBranchFromPullRequest();
//
//        if (!$baseBranch)
//        {
//            $baseBranch = 'develop';
//        }
//
//        // Get current base branch of the extension for Extension update test
//        if (is_dir('base'))
//        {
//            $this->taskDeleteDir('base')->run();
//        }
//
//        $this->_exec("git clone -b $baseBranch --single-branch --depth 1 git@github.com:redCOMPONENT-COM/redSHOPB2B.git base");
//        $this->say("Downloaded $baseBranch Branch for Update test");
//    }
//
//    private function dumpDatabase($file, $database_host = null)
//    {
//        if (is_file($file))
//        {
//            $this->taskFileSystemStack()->remove($file);
//        }
//
//        if (!isset($this->databaseConnection))
//        {
//            // Get connection details from the acceptance.suite.yml config file
//            $acceptanceSuite = Symfony\Component\Yaml\Yaml::parse(file_get_contents('acceptance.suite.yml'));
//            $this->databaseConnection = $acceptanceSuite['modules']['config']['JoomlaBrowser'];
//
//            if ($database_host)
//            {
//                // Override database host when in rare cases like running it in a Vagrant box
//                $this->databaseConnection["database host"] = $database_host;
//            }
//        }
//
//        try
//        {
//            $dump = new Ifsnop\Mysqldump\Mysqldump(
//                $this->databaseConnection["database name"],
//                $this->databaseConnection["database user"],
//                $this->databaseConnection["database password"],
//                $this->databaseConnection["database host"],
//                'mysql',
//                ['no-data' => true, 'skip-dump-date' => true]
//            );
//
//            $dump->start($file);
//        }
//        catch (\Exception $e)
//        {
//            $this->say('mysqldump-php error: ' . $e->getMessage());
//        }
//    }
//
//    /**
//     * Prepares the .zip packages of the extension to be installed in Joomla
//     *
//     * @param   string  $method  'phing' for package using phing. 'gulp' (Nodejs) is the default method
//     */
//    public function prepareReleasePackages($method = 'gulp')
//    {
//        if ('phing' == strtolower($method))
//        {
//            $this->_exec('vendor/bin/phing -f ../build/extension_packager.xml -Dadd-version-in-package=0');
//        }
//        else
//        {
//            // If not in Travis environment, it executes gulp release
//            if (!getenv('TRAVIS_PULL_REQUEST'))
//            {
//                $this->_exec("gulp release --skip-version --gulpfile ../build/gulpfile.js");
//            }
//        }
//    }
//
//    /**
//     * Looks for PHP Parse errors in core
//     */
//    public function checkForParseErrors()
//    {
//        // Script defines
//        define('REPO_BASE', dirname(__DIR__));
//
//        $this->say('Initializing PHP Parse Error checker.');
//
//        $paths = [
//            'extensions/components/com_rsbmedia/',
//            'extensions/components/com_redshopb/',
//            'extensions/libraries/',
//            'extensions/modules/',
//            'extensions/plugins/'
//        ];
//
//        foreach ($paths as $path)
//        {
//            $folderToCheck = REPO_BASE . '/' . $path;
//
//            if (!is_dir($folderToCheck))
//            {
//                $this->say("Folder: $folderToCheck does not exist");
//                continue;
//            }
//
//            $this->say("Checking errors at $folderToCheck");
//            $parseErrors = shell_exec('find ' . $folderToCheck .' -name "*.php" -exec php -l {} \; | grep "Parse error";');
//
//            if ($parseErrors)
//            {
//                $this->yell("Parse error found at: $parseErrors");
//                exit(0);
//            }
//        }
//
//        $this->say('There were no issues detected');
//    }
//
//    /**
//     * Looks for missed debug code like var_dump or console.log
//     */
//    public function checkForMissedDebugCode()
//    {
//        $this->_exec('php checkers/misseddebugcodechecker.php ../extensions/components/com_redshopb/ ../extensions/components/com_rsbmedia/ ../extensions/libraries/ ../extensions/modules/ ../extensions/plugins/');
//    }
//
//    /**
//     * Check the code style of the project against a passed sniffers
//     */
//    public function checkCodestyle()
//    {
//        if (!is_dir('checkers/phpcs/Joomla'))
//        {
//            $this->say('Downloading Joomla Coding Standards Sniffers');
//            $this->_exec("git clone -b master --single-branch --depth 1 https://github.com/joomla/coding-standards.git checkers/phpcs/Joomla");
//        }
//
//        $this->taskExec('php checkers/phpcs.php')
//            ->printed(true)
//            ->run();
//    }
//
//    /**
//     * Check if local OS is Windows
//     *
//     * @return bool
//     */
//    private function isWindows()
//    {
//        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
//    }
//
//    /**
//     * Get the correct CMS root path
//     *
//     * @return string
//     */
//    private function getCmsPath()
//    {
//        if (empty($this->configuration->cmsPath))
//        {
//            return 'joomla-cms3';
//        }
//
//        if (!file_exists(dirname($this->configuration->cmsPath)))
//        {
//            $this->say("Cms path written in local configuration does not exists or is not readable");
//
//            return 'joomla-cms3';
//        }
//
//        return $this->configuration->cmsPath;
//    }
//
//
//    /**
//     * Build correct git clone command according to local configuration and OS
//     *
//     * @return string
//     */
//    private function buildGitCloneCommand()
//    {
//        $branch = empty($this->configuration->branch) ? 'staging' : $this->configuration->branch;
//
//        return "git" . $this->executableExtension . " clone -b $branch --single-branch --depth 1 https://github.com/joomla/joomla-cms.git cache";
//    }
//
//    /**
//     * Edits the Joomla .htaccess file for use on virtualmin servers
//     *
//     * @param   string   $file   .htaccess file path
//     *
//     * @return  void
//     */
//    private function editHtaccess($file)
//    {
//        $htaccess = explode(PHP_EOL, file_get_contents($file));
//
//        $htaccess = preg_replace("/^Options \+FollowSymlinks/", 'Options +SymlinksIfOwnerMatch', $htaccess);
//
//        $write = file_put_contents($file, implode(PHP_EOL, $htaccess));
//
//        if (false === $write)
//        {
//            $this->say(".htaccess file could not be edited.");
//            exit(1);
//        }
//    }

}
