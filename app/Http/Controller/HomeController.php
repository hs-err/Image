<?php declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://swoft.org/docs
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace App\Http\Controller;

use App\GD\Base;
use App\GD\Draw;
use App\Model\Data\GoodsData;
use Swoft;
use Swoft\Http\Message\ContentType;
use Swoft\Http\Message\Request;
use Swoft\Http\Message\Response;
use Swoft\Http\Server\Annotation\Mapping\Controller;
use Swoft\Http\Server\Annotation\Mapping\RequestMapping;
use Swoft\View\Renderer;
use Throwable;
use function bean;
use function context;

/**
 * Class HomeController
 * @Controller()
 */
class HomeController
{
    /**
     * @RequestMapping("/")
     * @throws Throwable
     */
    public function index(Request $request): Response
    {
        Swoft\Log\Helper\Log::info(Base::res('main.json','default'));
        $content = (new Draw(
            Swoft\Co::readFile(Base::res('main.json','default')),
            substr(Swoft\Co::readFile(Base::res('main.php','default')),5),
            $request->getParsedQuery()
        ))->main();
        return context()->getResponse()->withContentType('image/png')->withContent($content);
    }

    /**
     * @RequestMapping("/hi")
     *
     * @return Response
     */
    public function hi(): Response
    {
        return context()->getResponse()->withContent('hi');
    }

    /**
     * @RequestMapping("/hello[/{name}]")
     * @param string $name
     *
     * @return Response
     */
    public function hello(string $name): Response
    {
        return context()->getResponse()->withContent('Hello' . ($name === '' ? '' : ", {$name}"));
    }

    /**
     * @RequestMapping("/wstest", method={"GET"})
     *
     * @return Response
     * @throws Throwable
     */
    public function wsTest(): Response
    {
        return view('home/ws-test');
    }

    /**
     * @RequestMapping("/dataConfig", method={"GET"})
     *
     * @return array
     * @throws Throwable
     */
    public function dataConfig(): array
    {
        return bean(GoodsData::class)->getConfig();
    }
}
