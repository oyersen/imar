  <?php
    class App
    {
        protected $controller = 'HomeController';
        protected $method = 'index';
        protected $params = [];

        public function __construct()
        {
            // URL parçalarını al
            $url = $this->parseUrl();

            // Controller kontrolü
            if (file_exists('app/controllers/' . $url[0] . '.php')) {
                $this->controller = $url[0];
                unset($url[0]);
            }
            require_once 'app/controllers/' . $this->controller . '.php';
            $this->controller = new $this->controller;

            // Method kontrolü
            if (isset($url[1]) && method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }

            // Parametreleri ayarla
            $this->params = $url ? array_values($url) : [];

            // Controller metodunu çağır
            call_user_func_array([$this->controller, $this->method], $this->params);
        }

        public function parseUrl()
        {
            if (isset($_GET['url'])) {
                return explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
            }
        }
    }
