<?php namespace App\Filters;

use App\Models\Admin\ConfigModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\App;
use Config\Services;

/**
 * Class DebugToolbar
 *
 * @package App\Filters
 */
class DebugToolbar implements FilterInterface
{
    /**
     * We don't need to do anything here.
     *
     * @param RequestInterface|\CodeIgniter\HTTP\IncomingRequest $request
     *
     * @return mixed
     */
    public function before(RequestInterface $request)
    {
    }

    //--------------------------------------------------------------------

    /**
     * If the debug flag is set (CI_DEBUG) then collect performance
     * and debug information and display it in a toolbar.
     *
     * @param RequestInterface|\CodeIgniter\HTTP\IncomingRequest $request
     * @param ResponseInterface|\CodeIgniter\HTTP\Response $response
     *
     * @return mixed
     * @throws \CodeIgniter\Database\Exceptions\DatabaseException
     */
    public function after(RequestInterface $request, ResponseInterface $response)
    {
        $config_model = new ConfigModel();

        $format = $response->getHeaderLine('content-type');

        if ($config_model->GetConfig('debug') == 1 && !is_cli() && CI_DEBUG) {
            global $app;

            $toolbar = Services::toolbar(new App());
            $stats = $app->getPerformanceStats();
            $data    = $toolbar->run(
                $stats['startTime'],
                $stats['totalTime'],
                $stats['startMemory'],
                $request,
                $response
            );

            helper(['filesystem']);

            // Updated to time() so we can get history
            $time = time();

            if (!is_dir(WRITEPATH . 'debugbar')) {
                if (!mkdir(WRITEPATH . 'debugbar', 0777) && !is_dir(WRITEPATH . 'debugbar')) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', WRITEPATH . 'debugbar'));
                }
            }

            write_file(WRITEPATH .'debugbar/'.'debugbar_' . $time, $data, 'w+');

            $format = $response->getHeaderLine('content-type');

            // Non-HTML formats should not include the debugbar
            // then we send headers saying where to find the debug data
            // for this response
            if ($request->isAJAX() || strpos($format, 'html') === false) {
                return $response->setHeader('Debugbar-Time', (string)$time)
                    ->setHeader('Debugbar-Link', site_url("?debugbar_time={$time}"))
                    ->getBody();
            }

            $script = PHP_EOL . '<script type="text/javascript" id="debugbar_loader" ' . 'data-time="' . $time . '" ' . 'src="' . rtrim(site_url(), '/') . '?debugbar"></script>' . PHP_EOL;

            if (strpos($response->getBody(), '</body>') !== false) {
                return $response->setBody(str_replace('</body>', $script . '</body>', $response->getBody()));
            }

            return $response->appendBody($script);
        }
    }

    //--------------------------------------------------------------------
}
