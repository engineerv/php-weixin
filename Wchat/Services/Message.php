<?php
/**
 * 微信消息管理组件
 * @author yyq
 * @version 1.0
 */
namespace Wchat\Services;

trait Message {

    /**
     * 事件列表
     * @var array
     */
    protected $messageEvents = array();

    /**
     * 绑定消息事件
     * @param string $events 多个用,隔开:event事件类型全部被转化成读取Event键当成事件
     * @param callable $callback 回调方法
     * @return $this
     */
    public function messageEvent($events, callable $callback) {
        foreach (explode(',', $events) as $event) {
            $this->messageEvents[$event] = $callback;
        }
        return $this;
    }

    /**
     * 事件监听响应
     * @return void
     */
    public function messageListen() {
        $data = $this->xmlDecode($this->getPush());
        $msgType = $data['MsgType'] == 'event' ? $data['Event'] : $data['MsgType'];

        if (isset($this->messageEvents[$msgType])) {
            $callback = $this->messageEvents[$msgType];
            $receive = $callback($data);

            $reply['ToUserName'] = $data['FromUserName'];
            $reply['FromUserName'] = $data['ToUserName'];
            $reply['CreateTime'] = time();
            $reply['MsgType'] = $receive['type'];

            switch ($receive['type']) {
                case 'text':
                    $reply['Content'] = $receive['Content'];
                    break;
                case 'image':
                    $reply['Image']['MediaId'] = $receive['MediaId'];
                    break;
                case 'voice':
                    $reply['Voice']['MediaId'] = $receive['MediaId'];
                    break;
                case 'video':
                    $reply['Voice']['MediaId'] = $receive['MediaId'];
                    $reply['Voice']['Title'] = $receive['Title'];
                    $reply['Voice']['Description'] = $receive['Description'];
                    break;
                case 'music':
                    $reply['Music']['Title'] = $receive['Title'];
                    $reply['Music']['Description'] = $receive['Description'];
                    $reply['Music']['MusicUrl'] = $receive['MusicUrl'];
                    $reply['Music']['HQMusicUrl'] = $receive['HQMusicUrl'];
                    $reply['Music']['ThumbMediaId'] = $receive['ThumbMediaId'];
                    break;
                case 'news':
                    $reply['ArticleCount'] = $receive['ArticleCount'];
                    $reply['Articles']['item'] = $receive['item'];
                    break;
            }

            echo $this->xmlEncode($reply);
        }
    }
}