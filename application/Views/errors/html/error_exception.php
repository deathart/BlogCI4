<?php $error_id = uniqid('error'); ?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">

    <title><?php echo htmlspecialchars($title, ENT_SUBSTITUTE, 'UTF-8'); ?></title>
    <style type="text/css">
        <?php echo preg_replace('#[\r\n\t ]+#', ' ', file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'debug.css')); ?>
    </style>

    <script type="text/javascript">
        <?php echo file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'debug.js'); ?>
    </script>
</head>
<body onload="init()">

<!-- Header -->
<div class="header">
    <div class="container">
        <h1><?php echo htmlspecialchars($title, ENT_SUBSTITUTE, 'UTF-8'), ($exception->getCode() ? ' #'.$exception->getCode() : ''); ?></h1>
        <p>
            <?php echo $exception->getMessage(); ?>
            <a href="https://www.google.com/search?q=<?php echo urlencode($title.' '.preg_replace('#\'.*\'|".*"#Us', '', $exception->getMessage())); ?>"
               rel="noreferrer" target="_blank">search &rarr;</a>
        </p>
    </div>
</div>

<!-- Source -->
<div class="container">
    <p><b><?php echo self::cleanPath($file, $line); ?></b> at line <b><?php echo $line; ?></b></p>

    <?php if (is_file($file)) : ?>
        <div class="source">
            <?php echo self::highlightFile($file, $line, 15); ?>
        </div>
    <?php endif; ?>
</div>

<div class="container">

    <ul class="tabs" id="tabs">
        <li><a href="#backtrace">Backtrace</a></li>
        <li><a href="#server">Server</a></li>
        <li><a href="#request">Request</a></li>
        <li><a href="#response">Response</a></li>
        <li><a href="#files">Files</a></li>
        <li><a href="#memory">Memory</a></li>
        </li>
    </ul>

    <div class="tab-content">

        <!-- Backtrace -->
        <div class="content" id="backtrace">

            <ol class="trace">
                <?php foreach ($trace as $index => $row) : ?>

                    <li>
                        <p>
                            <!-- Trace info -->
                            <?php if (isset($row['file']) && is_file($row['file'])) :?>
                                <?php
                                if (isset($row['function']) && in_array($row['function'], ['include', 'include_once', 'require', 'require_once'], true)) {
                                    echo $row['function'].' '. self::cleanPath($row['file']);
                                } else {
                                    echo self::cleanPath($row['file']).' : '.$row['line'];
                                }
                                ?>
                            <?php else : ?>
                                {PHP internal code}
                            <?php endif; ?>

                            <!-- Class/Method -->
                            <?php if (isset($row['class'])) : ?>
                            &nbsp;&nbsp;&mdash;&nbsp;&nbsp;<?php echo $row['class'].$row['type'].$row['function']; ?>
                            <?php if (!empty($row['args'])) : ?>
                            <?php $args_id = $error_id.'args'.$index; ?>
                            ( <a href="#" onclick="return toggle('<?php echo $args_id; ?>');">arguments</a> )
                        <div class="args" id="<?php echo $args_id; ?>">
                            <table cellspacing="0">

                                <?php
                                $params = null;
                                // Reflection by name is not available for closure function
                                if (substr($row['function'], -1) !== '}') {
                                    $mirror = isset($row['class']) ? new \ReflectionMethod($row['class'], $row['function']) : new \ReflectionFunction($row['function']);
                                    $params = $mirror->getParameters();
                                }
                                foreach ($row['args'] as $key => $value) : ?>
                                    <tr>
                                        <td><code><?php echo htmlspecialchars(isset($params[$key]) ? '$'.$params[$key]->name : "#$key", ENT_SUBSTITUTE, 'UTF-8'); ?></code></td>
                                        <td><pre><?php echo dd($value); ?></pre></td>
                                    </tr>
                                <?php endforeach; ?>

                            </table>
                        </div>
                        <?php else : ?>
                            ()
                        <?php endif; ?>
                        <?php endif; ?>

                        <?php if (!isset($row['class']) && isset($row['function'])) : ?>
                            &nbsp;&nbsp;&mdash;&nbsp;&nbsp;	<?php echo $row['function']; ?>()
                        <?php endif; ?>
                        </p>

                        <!-- Source? -->
                        <?php if (isset($row['file']) && is_file($row['file']) &&  isset($row['class'])) : ?>
                            <div class="source">
                                <?php echo self::highlightFile($row['file'], $row['line']); ?>
                            </div>
                        <?php endif; ?>
                    </li>

                <?php endforeach; ?>
            </ol>

        </div>

        <!-- Server -->
        <div class="content" id="server">
            <?php foreach (['_SERVER', '_SESSION'] as $var) : ?>
                <?php if (empty($GLOBALS[$var]) || !is_array($GLOBALS[$var])) {
                                    continue;
                                } ?>

                <h3>$<?php echo $var; ?></h3>

                <table>
                    <thead>
                    <tr>
                        <th>Key</th>
                        <th>Value</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($GLOBALS[$var] as $key => $value) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($key, ENT_IGNORE, 'UTF-8'); ?></td>
                            <td>
                                <?php if (is_string($value)) : ?>
                                    <?php echo htmlspecialchars($value, ENT_SUBSTITUTE, 'UTF-8'); ?>
                                <?php else: ?>
                                    <?php echo '<pre>'.print_r($value, true); ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

            <?php endforeach; ?>

            <!-- Constants -->
            <?php $constants = get_defined_constants(true); ?>
            <?php if (!empty($constants['user'])) : ?>
                <h3>Constants</h3>

                <table>
                    <thead>
                    <tr>
                        <th>Key</th>
                        <th>Value</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($constants['user'] as $key => $value) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($key, ENT_IGNORE, 'UTF-8'); ?></td>
                            <td>
                                <?php if (!is_array($value) && !is_object($value)) : ?>
                                    <?php echo htmlspecialchars($value, ENT_SUBSTITUTE, 'UTF-8'); ?>
                                <?php else: ?>
                                    <?php echo '<pre>'.print_r($value, true); ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <!-- Request -->
        <div class="content" id="request">
            <?php $request = \Config\Services::request(); ?>

            <table>
                <tbody>
                <tr>
                    <td style="width: 10em">Path</td>
                    <td><?php echo $request->uri; ?></td>
                </tr>
                <tr>
                    <td>HTTP Method</td>
                    <td><?php echo $request->getMethod(true); ?></td>
                </tr>
                <tr>
                    <td>IP Address</td>
                    <td><?php echo $request->getIPAddress(); ?></td>
                </tr>
                <tr>
                    <td style="width: 10em">Is AJAX Request?</td>
                    <td><?php echo $request->isAJAX() ? 'yes' : 'no'; ?></td>
                </tr>
                <tr>
                    <td>Is CLI Request?</td>
                    <td><?php echo $request->isCLI() ? 'yes' : 'no'; ?></td>
                </tr>
                <tr>
                    <td>Is Secure Request?</td>
                    <td><?php echo $request->isSecure() ? 'yes' : 'no'; ?></td>
                </tr>
                <tr>
                    <td>User Agent</td>
                    <td><?php echo $request->getUserAgent()->getAgentString(); ?></td>
                </tr>

                </tbody>
            </table>


            <?php $empty = true; ?>
            <?php foreach (['_GET', '_POST', '_COOKIE'] as $var) : ?>
                <?php if (empty($GLOBALS[$var]) || !is_array($GLOBALS[$var])) {
                                    continue;
                                } ?>

                <?php $empty = false; ?>

                <h3>$<?php echo $var; ?></h3>

                <table style="width: 100%">
                    <thead>
                    <tr>
                        <th>Key</th>
                        <th>Value</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($GLOBALS[$var] as $key => $value) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($key, ENT_IGNORE, 'UTF-8'); ?></td>
                            <td>
                                <?php if (!is_array($value) && !is_object($value)) : ?>
                                    <?php echo htmlspecialchars($value, ENT_SUBSTITUTE, 'UTF-8'); ?>
                                <?php else: ?>
                                    <?php echo '<pre>'.print_r($value, true); ?>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

            <?php endforeach; ?>

            <?php if ($empty) : ?>

                <div class="alert">
                    No $_GET, $_POST, or $_COOKIE Information to show.
                </div>

            <?php endif; ?>

            <?php $headers = $request->getHeaders(); ?>
            <?php if (!empty($headers)) : ?>

                <h3>Headers</h3>

                <table>
                    <thead>
                    <tr>
                        <th>Header</th>
                        <th>Value</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($headers as $name => $value) : ?>
                        <?php if (empty($value)) {
                                    continue;
                                } ?>
                        <?php if (!is_array($value)) {
                                    $value = [$value];
                                } ?>
                        <?php foreach ($value as $h) : ?>
                            <tr>
                                <td><?php echo esc($h->getName(), 'html'); ?></td>
                                <td><?php echo esc($h->getValueLine(), 'html'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                    </tbody>
                </table>

            <?php endif; ?>
        </div>

        <!-- Response -->
        <?php
        $response = \Config\Services::response();
        $response->setStatusCode(http_response_code());
        ?>
        <div class="content" id="response">
            <table>
                <tr>
                    <td style="width: 15em">Response Status</td>
                    <td><?php echo $response->getStatusCode().' - '.$response->getReason(); ?></td>
                </tr>
            </table>

            <?php $headers = $response->getHeaders(); ?>
            <?php if (!empty($headers)) : ?>
                <?php natsort($headers); ?>

                <h3>Headers</h3>

                <table>
                    <thead>
                    <tr>
                        <th>Header</th>
                        <th>Value</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($headers as $name => $value) : ?>
                        <tr>
                            <td><?php echo esc($name, 'html'); ?></td>
                            <td><?php echo esc($response->getHeaderLine($name), 'html'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

            <?php endif; ?>
        </div>

        <!-- Files -->
        <div class="content" id="files">
            <?php $files = get_included_files(); ?>

            <ol>
                <?php foreach ($files as $file) :?>
                    <li><?php echo htmlspecialchars(self::cleanPath($file), ENT_SUBSTITUTE, 'UTF-8'); ?></li>
                <?php endforeach; ?>
            </ol>
        </div>

        <!-- Memory -->
        <div class="content" id="memory">

            <table>
                <tbody>
                <tr>
                    <td>Memory Usage</td>
                    <td><?php echo self::describeMemory(memory_get_usage(true)); ?></td>
                </tr>
                <tr>
                    <td style="width: 12em">Peak Memory Usage:</td>
                    <td><?php echo self::describeMemory(memory_get_peak_usage(true)); ?></td>
                </tr>
                <tr>
                    <td>Memory Limit:</td>
                    <td><?php echo ini_get('memory_limit'); ?></td>
                </tr>
                </tbody>
            </table>

        </div>

    </div>  <!-- /tab-content -->

</div> <!-- /container -->

<div class="footer">
    <div class="container">

        <p>
            Displayed at <?php echo date('H:i:sa'); ?> &mdash;
            PHP: <?php echo PHP_VERSION; ?>  &mdash;
            CodeIgniter: <?php echo \CodeIgniter\CodeIgniter::CI_VERSION; ?>
        </p>

    </div>
</div>

</body>
</html>
