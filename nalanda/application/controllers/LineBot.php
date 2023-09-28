<?php

use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot as line;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuSizeBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuAreaBoundsBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuAreaBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\RichMenuBuilder;
use LINE\LINEBot\SignatureValidator;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;

class LineBot extends Base_Controller
{
    private $ChannelSecret;
    private $ChannelAccessToken;
    private $bot;

    public function __construct()
    {
        parent::__construct();
        $this->ChannelSecret = $this->config->config['ChannelSecret'];
        $this->ChannelAccessToken = $this->config->config['ChannelAccessToken'];

        $body = file_get_contents('php://input');
        $line_header = $_SERVER['HTTP_X_LINE_SIGNATURE'];

        $sign = new SignatureValidator();
        // file_put_contents('test.log', $body, FILE_APPEND);
        // file_put_contents('test.log', $line_header, FILE_APPEND);
        if (!$sign->validateSignature($body, $this->ChannelSecret, $line_header)) {
            file_put_contents('error_request.log', date('Y-m-d H:i:s') . ' bad request', FILE_APPEND);
            exit;
        }
        $httpClient = new CurlHTTPClient($this->ChannelAccessToken);
        $this->bot = new line($httpClient, ['channelSecret' => $this->ChannelSecret]);
    }

    public function index()
    {
        $HttpRequestBody = file_get_contents('php://input');
        $HeaderSignature = $_SERVER['HTTP_X_LINE_SIGNATURE'];
        $this->bot->parseEventRequest($HttpRequestBody, $HeaderSignature);
        file_put_contents('log.txt', $HttpRequestBody . PHP_EOL, FILE_APPEND);

        $data = json_decode($HttpRequestBody, true);
        foreach ($data['events'] as $event)
            switch ($event['type']) {
                case 'message':
                    $this->messageHandler($event);
                    break;
                case 'postback':
                    $this->postbackHandler($event);
                    break;
                default:
                    $this->defaultReply($event['replyToken']);
                    break;
            }
    }

    public function set_rich_menu()
    {
        $size = new RichMenuSizeBuilder(0, 0);

        $areas = [];

        // 設定bounds
        $bounds = new RichMenuAreaBoundsBuilder(0, 0, 625, 843);
        $action = new PostbackTemplateActionBuilder('認識那瀾陀菩提', 'know', '認識那瀾陀菩提');
        $areas[] = new RichMenuAreaBuilder($bounds, $action);

        $bounds = new RichMenuAreaBoundsBuilder(625, 0, 625, 843);
        $action = new PostbackTemplateActionBuilder('虛空藏護法計畫', 'plan', '虛空藏護法計畫');
        $areas[] = new RichMenuAreaBuilder($bounds, $action);

        $bounds = new RichMenuAreaBoundsBuilder(1250, 0, 625, 843);
        $action = new PostbackTemplateActionBuilder('近期活動', 'event', '近期活動');
        $areas[] = new RichMenuAreaBuilder($bounds, $action);

        $bounds = new RichMenuAreaBoundsBuilder(1875, 0, 625, 843);
        $action = new PostbackTemplateActionBuilder('消災祈福', 'prayer', '消災祈福');
        $areas[] = new RichMenuAreaBuilder($bounds, $action);

        $bounds = new RichMenuAreaBoundsBuilder(0, 843, 625, 843);
        $action = new PostbackTemplateActionBuilder('虛空光明建設', 'building', '虛空光明建設');
        $areas[] = new RichMenuAreaBuilder($bounds, $action);

        $bounds = new RichMenuAreaBoundsBuilder(625, 843, 625, 843);
        $action = new PostbackTemplateActionBuilder('虛空會談', 'talking', '虛空會談');
        $areas[] = new RichMenuAreaBuilder($bounds, $action);

        $bounds = new RichMenuAreaBoundsBuilder(1250, 843, 625, 843);
        $action = new PostbackTemplateActionBuilder('會員中心', 'user_info', '會員中心');
        $areas[] = new RichMenuAreaBuilder($bounds, $action);

        $bounds = new RichMenuAreaBoundsBuilder(1875, 843, 625, 843);
        $action = new PostbackTemplateActionBuilder('我的修學', 'school', '我的修學');
        $areas[] = new RichMenuAreaBuilder($bounds, $action);

        $richMenu = new RichMenuBuilder($size->getFull(), false, '選單', '選單', $areas);
        $res = $this->bot->createRichMenu($richMenu);
        $body = $res->getRawBody();
        $this->output(true, 'success', compact('res', 'body'));
    }

    public function del_rich_menu()
    {
        $richMenuId = $this->input->post('richMenuId');
        $res = $this->bot->deleteRichMenu($richMenuId);

        $this->output(true, 'test', ['res' => $res->getRawBody()]);
    }

    public function uploadRichMenuImage()
    {
        $richMenuId = $this->input->post('richMenuId');
        $res = $this->bot->uploadRichMenuImage($richMenuId, './uploads/dist/rich_menu_.png', 'image/png');
        $this->output(true, 'test', ['res' => $res->getRawBody()]);
    }

    public function setDefaultRichMenuId()
    {
        $richMenuId = $this->input->post('richMenuId');
        $res = $this->bot->setDefaultRichMenuId($richMenuId);
        $this->output(true, 'test', ['res' => $res->getRawBody()]);
    }

    protected function messageHandler($event)
    {
        switch ($event['message']['text']) {
            case '仁波切開示':
                $message = new VideoMessageBuilder(base_url('uploads/dist/template_l.mp4'), base_url('uploads/dist/rich_menu.png'));
                $this->bot->replyMessage($event['replyToken'], $message);
                break;
            case '仁波切文字與影片':
                $this->bot->replyText($event['replyToken'], "仁波切文字與影片\nhttps://www.youtube.com/watch?v=LcHYvT8fRPI");
                break;
            case '姚仁喜建築理念':
                $this->bot->replyText($event['replyToken'], "姚仁喜建築理念\nhttps://www.youtube.com/watch?v=LcHYvT8fRPI");
                break;
            case '關於虛空藏護法計畫':
                $message = new ImageMessageBuilder(base_url('uploads/dist/rich_menu.png'), base_url('uploads/dist/rich_menu.png'));
                $this->bot->replyMessage($event['replyToken'], $message);
                break;
            default:
                $this->bot->replyText($event['replyToken'], $event['message']['text']);
                break;
        }
    }

    protected function postbackHandler($event)
    {
        switch ($event['postback']['data']) {
                // 認識那瀾陀菩提
            case 'know':
                $this->know($event);
                break;

                // 了解仁波切
            case 'seven_know':
                $this->seven_know($event);
                break;

                // 關於新的研究所
            case 'mind':
                $this->mind($event);
                break;

                // 聯絡資訊
            case 'contact_info':
                $this->contact_info($event);
                break;

                // 虛空光明建設
            case 'building':
                $this->building($event);
                break;

                // 緣起
            case 'start':
                $this->start($event);
                break;

                // 虛空藏護法計畫
            case 'plan':
                $this->plan($event);
                break;

                // 近期活動
            case 'event':
                $this->event($event);
                break;

                // 近期活動
            case 'signUp':
                $this->signUp($event);
                break;

            default:
                $this->defaultReply($event['replyToken']);
                break;
        }
    }

    protected function know($event)
    {
        $actions[] = new UriTemplateActionBuilder('了解更多', 'https://www.nbtaichung.org/about');
        $templates[] = new CarouselColumnTemplateBuilder('關於那瀾陀菩提', '文字描述那瀾陀，文字描述文字描述。', base_url('uploads/dist/Frame_33.png'), $actions);
        $actions = [];

        $actions[] = new PostbackTemplateActionBuilder('了解更多', 'seven_know', '了解竹慶本樂仁波切');
        $templates[] = new CarouselColumnTemplateBuilder('第七世竹慶本樂仁波切', '文字描述那瀾陀，文字描述文字描述。', base_url('uploads/dist/rich_menu.png'), $actions);
        $actions = [];

        $actions[] = new UriTemplateActionBuilder('了解更多', 'https://www.nbtaichung.org/team-list');
        $templates[] = new CarouselColumnTemplateBuilder('師長介紹', '文字描述那瀾陀，文字描述文字描述。', base_url('uploads/dist/rich_menu.png'), $actions);
        $actions = [];

        $actions[] = new PostbackTemplateActionBuilder('了解更多', 'mind', '關於心的研究所');
        $templates[] = new CarouselColumnTemplateBuilder('關於心的研究所', '文字描述那瀾陀，文字描述文字描述。', base_url('uploads/dist/rich_menu.png'), $actions);
        $actions = [];

        $actions[] = new PostbackTemplateActionBuilder('了解更多', 'contact_info', '聯絡資訊');
        $templates[] = new CarouselColumnTemplateBuilder('聯絡資訊', '文字描述那瀾陀，文字描述文字描述。', base_url('uploads/dist/rich_menu.png'), $actions);
        $actions = [];

        $message = new TemplateMessageBuilder('認識那瀾陀菩提', new CarouselTemplateBuilder($templates));
        $res = $this->bot->replyMessage($event['replyToken'], $message);
        file_put_contents('log.txt', json_encode($res->getRawBody(), 288), FILE_APPEND);
    }

    // 了解仁波切
    protected function seven_know($event)
    {
        $baseSize = new BaseSizeBuilder(560, 1040);
        $actions[] = new ImagemapMessageActionBuilder('仁波切開示', new AreaBuilder(0, 0, 520, 560));
        $actions[] = new ImagemapUriActionBuilder('https://www.nbtaichung.org/team-list', new AreaBuilder(520, 0, 520, 560));
        $message = new ImagemapMessageBuilder(base_url('uploads/dist/Frame_27.png#'), '了解竹慶本樂仁波切', $baseSize, $actions);
        $res = $this->bot->replyMessage($event['replyToken'], $message);
        // file_put_contents('log.txt', json_encode($res->getRawBody(), 288), FILE_APPEND);
    }

    // 關於心的研究所
    protected function mind($event)
    {
        $actions[] = new UriTemplateActionBuilder('立即前往', 'https://www.nbtaichung.org/heart');
        $templates[] = new CarouselColumnTemplateBuilder('課程緣起', '文字描述那瀾陀，文字描述文字描述。', base_url('uploads/dist/image_21.png'), $actions);
        $actions = [];

        $actions[] = new UriTemplateActionBuilder('立即前往', 'https://www.nbtaichung.org/product-list/heart-course');
        $templates[] = new CarouselColumnTemplateBuilder('課程報名', '文字描述那瀾陀，文字描述文字描述。', base_url('uploads/dist/image_37.png'), $actions);
        $actions = [];

        $actions[] = new UriTemplateActionBuilder('立即前往', 'https://www.nbtaichung.org/y2b-list/essence');
        $templates[] = new CarouselColumnTemplateBuilder('課程精華', '文字描述那瀾陀，文字描述文字描述。', base_url('uploads/dist/image_19.png'), $actions);
        $actions = [];

        $message = new TemplateMessageBuilder('關於心的研究所', new CarouselTemplateBuilder($templates));
        $res = $this->bot->replyMessage($event['replyToken'], $message);
        // file_put_contents('log.txt', json_encode($res->getRawBody(), 288), FILE_APPEND);
    }

    // 聯絡資訊
    protected function contact_info($event)
    {
        $this->bot->replyText($event['replyToken'], "洽詢電話：(04) 2236-0016\n海外請撥：+886 4 22360016\n地址：(406) 臺中市北屯區崇\n德路一段586號7樓\nEmail：nbtaichung01@gmail.com");
    }

    // 虛空光明建設
    protected function building($event)
    {
        $actions[] = new PostbackTemplateActionBuilder('立即前往', 'start', '緣起');
        $templates[] = new CarouselColumnTemplateBuilder('緣起', '文字描述那瀾陀，文字描述文字描述。', base_url('uploads/dist/image_22.png'), $actions);
        $actions = [];

        $actions[] = new UriTemplateActionBuilder('立即前往', 'https://www.nbtaichung.org/build');
        $templates[] = new CarouselColumnTemplateBuilder('進度說明', '文字描述那瀾陀，文字描述文字描述。', base_url('uploads/dist/image_24.png'), $actions);
        $actions = [];

        $actions[] = new PostbackTemplateActionBuilder('立即前往', 'end', '虛空光明開山記');
        $templates[] = new CarouselColumnTemplateBuilder('虛空光明開山記', '文字描述那瀾陀，文字描述文字描述。', base_url('uploads/dist/image_23.png'), $actions);
        $actions = [];

        $message = new TemplateMessageBuilder('虛空光明建設', new CarouselTemplateBuilder($templates));
        $res = $this->bot->replyMessage($event['replyToken'], $message);
    }

    // 緣起
    protected function start($event)
    {
        $actions[] = new MessageTemplateActionBuilder('立即前往', '仁波切文字與影片');
        $templates[] = new CarouselColumnTemplateBuilder('仁波切文字與影片', '文字描述那瀾陀，文字描述文字描述。', base_url('uploads/dist/image_22.png'), $actions);
        $actions = [];

        $actions[] = new MessageTemplateActionBuilder('了解更多', '姚仁喜建築理念');
        $templates[] = new CarouselColumnTemplateBuilder('姚仁喜建築理念', '文字描述那瀾陀，文字描述文字描述。', base_url('uploads/dist/image_24.png'), $actions);
        $actions = [];

        $message = new TemplateMessageBuilder('緣起', new CarouselTemplateBuilder($templates));
        $res = $this->bot->replyMessage($event['replyToken'], $message);
    }

    // 虛空藏護法計畫
    protected function plan($event)
    {
        // FIXME: 網址要改
        $actions[] = new UriTemplateActionBuilder('立即前往', 'https://www.google.com.tw');
        $templates[] = new CarouselColumnTemplateBuilder('目錄', '文字描述那瀾陀，文字描述文字描述。', base_url('uploads/dist/image_99.png'), $actions);
        $actions = [];

        $actions[] = new MessageTemplateActionBuilder('了解更多', '關於虛空藏護法計畫');
        $templates[] = new CarouselColumnTemplateBuilder('關於虛空藏護法計畫', '文字描述那瀾陀，文字描述文字描述。', base_url('uploads/dist/image_100.png'), $actions);
        $actions = [];

        $message = new TemplateMessageBuilder('虛空藏護法計畫', new CarouselTemplateBuilder($templates));
        $res = $this->bot->replyMessage($event['replyToken'], $message);
    }

    // 近期活動
    protected function event($event)
    {
        $actions[] = new PostbackTemplateActionBuilder('查看更多', 'signUp', '活動報名');
        $templates[] = new CarouselColumnTemplateBuilder('活動報名', '文字描述那瀾陀，文字描述文字描述。', base_url('uploads/dist/image_27.png'), $actions);
        $actions = [];

        $actions[] = new UriTemplateActionBuilder('立即前往', 'https://www.nbtaichung.org/gokind');
        $templates[] = new CarouselColumnTemplateBuilder('GO KIND', '文字描述那瀾陀，文字描述文字描述。', base_url('uploads/dist/image_28.png'), $actions);
        $actions = [];

        $actions[] = new UriTemplateActionBuilder('立即前往', 'https://www.nbtaichung.org/post-list/events');
        $templates[] = new CarouselColumnTemplateBuilder('活動回顧', '文字描述那瀾陀，文字描述文字描述。', base_url('uploads/dist/image_29.png'), $actions);
        $actions = [];

        $message = new TemplateMessageBuilder('近期活動', new CarouselTemplateBuilder($templates));
        $res = $this->bot->replyMessage($event['replyToken'], $message);
    }

    // 活動報名
    protected function signUp($event)
    {
        // TODO: 要串接活動
        $events = [
            [
                'cover'   => base_url('uploads/dist/image_29.png'),
                'title'   => '心的研究所線上止觀一日禪第9會',
                'content' => '文字描述那瀾陀，文字描述文字描述。',
                'url'     => 'https://www.google.com/',
            ],
            [
                'cover'   => base_url('uploads/dist/image_29.png'),
                'title'   => '心的研究所線上止觀一日禪第8會',
                'content' => '文字描述那瀾陀，文字描述文字描述。',
                'url'     => 'https://www.google.com/',
            ],
            [
                'cover'   => base_url('uploads/dist/image_29.png'),
                'title'   => '心的研究所線上止觀一日禪第7會',
                'content' => '文字描述那瀾陀，文字描述文字描述。',
                'url'     => 'https://www.google.com/',
            ],
        ];

        $templates = [];
        foreach ($events as $item) {
            $actions = [];
            $actions[] = new UriTemplateActionBuilder('立即報名', $item['url']);
            $templates[] = new CarouselColumnTemplateBuilder($item['title'], $item['content'], $item['cover'], $actions);
        }

        $message = new TemplateMessageBuilder('活動報名', new CarouselTemplateBuilder($templates));
        $res = $this->bot->replyMessage($event['replyToken'], $message);
        file_put_contents('log.txt', json_encode($res->getRawBody(), 288), FILE_APPEND);
    }

    protected function defaultReply($replyToken)
    {
        $this->bot->replyText($replyToken, '功能尚未開發');
    }
}
