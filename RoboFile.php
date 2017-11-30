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

        $this->taskCodecept()
            ->arg('--steps')
            ->arg('--debug')
            ->arg('--tap')
            ->arg('tests/acceptance/AdminLoginCest.php')
            ->run()
            ->stopOnFail();

        $this->taskCodecept()
            ->arg('--steps')
            ->arg('--debug')
            ->arg('--tap')
            ->arg('tests/acceptance/NewTourSteps.php')
            ->run()
            ->stopOnFail();

        $this->killSelenium();
    }

    /**
     * Function to Run tests in a Group
     *
     * @param   int     $use_htaccess     (1/0) Rename and enable embedded Joomla .htaccess file
     * @param   string  $package_method   Optional. Availabe methods are "phing" or "gulp". "gulp" by default
     * @param   string  $database_host    Optional. If using Joomla Vagrant Box do: $ vendor/bin/robo 0 gulp run:tests 33.33.33.58
     * @param   bool    $addCertificates  Optional.  Adds the certificates to the Joomla-provided PEM file
     *
     * @return void
     */
   /**
     * Stops Selenium Standalone Server
     *
     * @return void
     */
    public function killSelenium()
    {
        $this->_exec('curl http://localhost:4444/selenium-server/driver/?cmd=shutDownSeleniumServer');
    }

    /**
     * Downloads Composer
     *
     * @return void
     */
    private function getComposer()
    {
        // Make sure we have Composer
        if (!file_exists('./composer.phar'))
        {
            $this->_exec('curl --retry 3 --retry-delay 5 -sS https://getcomposer.org/installer | php');
        }
    }

    /**
     * Runs Selenium Standalone Server.
     *
     * @return void
     */
    public function runSelenium()
    {
        $this->_exec("vendor/bin/selenium-server-standalone -debug >> selenium.log 2>&1 &");
    }


    private function travisGetBaseBranchFromPullRequest()
    {
        if (!getenv('TRAVIS_PULL_REQUEST'))
        {
            return false;
        }

        $githubToken = getenv('GITHUB_TOKEN');
        $repoOwner = getenv('ORGANIZATION');
        $repo = getenv('REPO');
        $pull = getenv('TRAVIS_PULL_REQUEST');

        $client = new \Github\Client;
        $client->authenticate($githubToken, \Github\Client::AUTH_HTTP_TOKEN);
        $pullRequest = $client->api('pullRequest')
            ->show(
                $repoOwner, $repo, $pull
            );

        return $pullRequest['base']['ref'];
    }



    /**
     * Prepares the .zip packages of the extension to be installed in Joomla
     *
     * @param   string  $method  'phing' for package using phing. 'gulp' (Nodejs) is the default method
     */

    /**
     * Looks for PHP Parse errors in core
     */

    /**
     * Looks for missed debug code like var_dump or console.log
     */
    public function checkForMissedDebugCode()
    {
        $this->_exec('php checkers/misseddebugcodechecker.php ../extensions/components/com_redshopb/ ../extensions/components/com_rsbmedia/ ../extensions/libraries/ ../extensions/modules/ ../extensions/plugins/');
    }

    /**
     * Check the code style of the project against a passed sniffers
     */
    public function checkCodestyle()
    {
        if (!is_dir('checkers/phpcs/Joomla'))
        {
            $this->say('Downloading Joomla Coding Standards Sniffers');
            $this->_exec("git clone -b master --single-branch --depth 1 https://github.com/joomla/coding-standards.git checkers/phpcs/Joomla");
        }

        $this->taskExec('php checkers/phpcs.php')
            ->printed(true)
            ->run();
    }

    /**
     * Check if local OS is Windows
     *
     * @return bool
     */
    private function isWindows()
    {
        return strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';
    }


    /**
     * Build correct git clone command according to local configuration and OS
     *
     * @return string
     */

    /**
     * Edits the Joomla .htaccess file for use on virtualmin servers
     *
     * @param   string   $file   .htaccess file path
     *
     * @return  void
     */
}
