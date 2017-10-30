<?php

namespace Yoda\Libraries;

use Carbon\Carbon;
use League\Url\Url as LeagueUrl;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\ErrorEvent;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Message\Response;
use GuzzleHttp\Pool;
use GuzzleHttp\Stream\Stream;
use Evansims\Socialworth\Socialworth;
use Embed\Embed;
use ApiClients;
use Yoda\Models\Host;

class Url
{    
    static public function getHost($url)
    {
        $hosts = \Cache::remember('hosts', 10, function() {
            return Host::all();
        });

        $found = $hosts->search(function($item, $key) use ($url) {
            return strpos($url, $item->id) !== false;
        });

        if ($found) return $hosts[$found];
    }

    static public function getShares($url)
    {
        $socialworth = new Socialworth($url, ['twitter', 'facebook', 'linkedin']);
        $socialworth->setClient(ApiClients::basic());

        return $socialworth->all() ? $socialworth->all()->total : 0;
    }

    static public function getEmbed($url = null, $html = null)
    {
        try {
            $client = ApiClients::basic();

            if ($html) {
                $client->getEmitter()->on('before', function(BeforeEvent $e) use ($html) {
                    $body = isset($html) ? Stream::factory($html) : null;
                    $e->intercept(new Response(200, [], $body));
                });
            }
            
            return Embed::create($url ?: '', [
                'resolver' => [ 
                    'class' => \Embed\RequestResolvers\Guzzle5::class,
                    'config' => [ 'client' => $client ],
                ],
                'image' => [
                    'class' => 'Embed\ImageInfo\Guzzle5',
                    'config' => [ 'client' => ApiClients::basic() ],
                 ]
            ]);
        
        } catch (\Exception $e) {
            return false;
        }         
    }

    static public function expand($url)
    {
        if ( !static::isShortened($url)) return $url;

        try {
            return ApiClients::basic()->get($url)->getEffectiveUrl();

        } catch (RequestException $e) {
            return;
        }
    }

    static protected function clean($url)
    {
        $object = LeagueUrl::createFromUrl($url);

        $object->setQuery(
            array_except($object->getQuery(), static::$removableKeys)
        );

        $url = (String) $object;

        return strtok($url, "#");
    }

    static public function expandAndClean($url)
    {
        return static::clean(static::expand($url));
    }

    static public function expandAndCleanMultiple(array $urls)
    {
        $client   = ApiClients::basic();
        $requests = [];
        $out      = [];

        foreach ($urls as $url) {
            if (static::isShortened($url)) {
                $request = $client->createRequest('GET', $url);
                $request->setHeader('origin', $url);
                $requests[] = $request;

            } else {
                $out[$url] = static::clean($url);
            }
        }

        // if shortened: get expanded
        Pool::batch($client, $requests, [
            'complete' => function(CompleteEvent $e) use (&$out) {
                $origin   = $e->getRequest()->getHeader('origin');
                $expanded = $e->getResponse()->getEffectiveUrl();

                $out[$origin] = static::clean($expanded);
            },
            'error' => function (ErrorEvent $e) {
                $origin   = $e->getRequest()->getHeader('origin');
                $out[$origin] = null;
            }
        ]);
        
        return $out;
    }

    static public function isShortened($url)
    {
        $array = array_merge(
            static::$shortenedRegexApp, 
            static::$shortenedRegexVendor
        );

        return preg_match($array, $url);
    }

    static protected function makeRegex($hosts)
    {
        foreach ($hosts as $key => $host) {
            $host = str_replace('.', '\.', $host);
            $hosts[$key] = sprintf('(?:%s)', $host);
        }

        $string = implode('|', $hosts);

        return sprintf('/(?:$hosts?:\/\/)?(?:%s)\/[a-z0-9]*/', $string);
    }

    static protected $removableKeys = [
        'utm_source',
        'utm_content',
        'utm_campaign',
        'utm_medium',
        'utm_term',
        'mc_cid',
        'mc_eid',
        'CMP',
        'PMSRC_CAMPAIGN',
        'ncid',
        'xtor',
    ];

    static protected $shortenedRegexApp = [
        'liven.ws',
        'ppr.li',
        'toi.sr',
        'apr1.org',
        'bbc.in',
        'bloom.bg',
        'buff.ly',
        'buffy.it',
        'buffy.ly',
        'buzz.mw',
        'bzfd.it',
        'ccompt.es',
        'corsem.co',
        'ebx.sh',
        'echo.st',
        'emc.im',
        'eur1.fr',
        'evn.im',
        'f24.my',
        'fdip.fr',
        'fdw.lu',
        'for.tn',
        'francetv.in',
        'getpocket.com',
        'go.shr.lc',
        'gq.fr',
        'ht.ly',
        'hubs.li',
        'i100.io',
        'ift.tt',
        'ind.pn',
        'jim.fr',
        'l-obs.fr',
        'lc.cx',
        'lemde.fr',
        'lepoint.fr',
        'lequi.pe',
        'lesechos.fr',
        'lesechos.fr',
        'lext.so',
        'ln.is',
        'mdpt.fr',
        'minilien.fr',
        'n.mynews.ly',
        'nyv.me',
        'nzzl.us',
        'oe.cd',
        'opi.link',
        'oran.ge',
        'owl.li',
        'paper.li',
        'pllqt.it',
        'po.st',
        'recruit2.info',
        'rfi.my',
        'sco.it',
        'sco.lt',
        'snpy.tv',
        'sptnkne.ws',
        'ti.me',
        'tmblr.co',
        'trib.al',
        'u.afp.com',
        'url.exen.fr',
        'urlz.fr',
        'w.lpnt.fr',
        'webcpl.eu',
        'xfru.it',
        'zdnet.fr',
        'zdnet.net',
        'spr.ly',
        'ra.gy',
    ];

    // https://gist.github.com/KuroTsuto/8448070    
    static public $shortenedRegexVendor = [ 'rz.tw', '1link.in', '1url.com', '2.gp', '2big.at', '2tu.us', '3.ly', '307.to', '4ms.me', '4sq.com', '4url.cc', '6url.com', '7.ly', 'a.gg', 'a.nf', 'aa.cx', 'abcurl.net', 'ad.vu', 'adf.ly', 'adjix.com', 'afx.cc', 'all.fuseurl.com', 'alturl.com', 'amzn.to', 'ar.gy', 'arst.ch', 'atu.ca', 'azc.cc', 'b23.ru', 'b2l.me', 'bacn.me', 'bcool.bz', 'binged.it', 'bit.ly', 'bizj.us', 'bloat.me', 'bravo.ly', 'bsa.ly', 'budurl.com', 'canurl.com', 'chilp.it', 'chzb.gr', 'cl.lk', 'cl.ly', 'clck.ru', 'cli.gs', 'cliccami.info', 'clickthru.ca', 'clop.in', 'conta.cc', 'cort.as', 'cot.ag', 'crks.me', 'ctvr.us', 'cutt.us', 'dai.ly', 'decenturl.com', 'dfl8.me', 'digbig.com', 'digg.com', 'disq.us', 'dld.bz', 'dlvr.it', 'do.my', 'doiop.com', 'dopen.us', 'easyuri.com', 'easyurl.net', 'eepurl.com', 'eweri.com', 'fa.by', 'fav.me', 'fb.me', 'fbshare.me', 'ff.im', 'fff.to', 'fire.to', 'firsturl.de', 'firsturl.net', 'flic.kr', 'flq.us', 'fly2.ws', 'fon.gs', 'freak.to', 'fuseurl.com', 'fuzzy.to', 'fwd4.me', 'fwib.net', 'g.ro.lt', 'gizmo.do', 'gl.am', 'go.9nl.com', 'go.ign.com', 'go.usa.gov', 'goo.gl', 'goshrink.com', 'gurl.es', 'hex.io', 'hiderefer.com', 'hmm.ph', 'href.in', 'hsblinks.com', 'htxt.it', 'huff.to', 'hulu.com', 'hurl.me', 'hurl.ws', 'icanhaz.com', 'idek.net', 'ilix.in', 'is.gd', 'its.my', 'ix.lt', 'j.mp', 'jijr.com', 'kl.am', 'klck.me', 'korta.nu', 'krunchd.com', 'l9k.net', 'lat.ms', 'liip.to', 'liltext.com', 'linkbee.com', 'linkbun.ch', 'liurl.cn', 'ln-s.net', 'ln-s.ru', 'lnk.gd', 'lnk.ms', 'lnkd.in', 'lnkurl.com', 'lru.jp', 'lt.tl', 'lurl.no', 'macte.ch', 'mash.to', 'merky.de', 'migre.me', 'miniurl.com', 'minurl.fr', 'mke.me', 'moby.to', 'moourl.com', 'mrte.ch', 'myloc.me', 'myurl.in', 'n.pr', 'nbc.co', 'nblo.gs', 'nn.nf', 'not.my', 'notlong.com', 'nsfw.in', 'nutshellurl.com', 'nxy.in', 'nyti.ms', 'o-x.fr', 'oc1.us', 'om.ly', 'omf.gd', 'omoikane.net', 'on.cnn.com', 'on.mktw.net', 'onforb.es', 'orz.se', 'ow.ly', 'ping.fm', 'pli.gs', 'pnt.me', 'politi.co', 'post.ly', 'pp.gg', 'profile.to', 'ptiturl.com', 'pub.vitrue.com', 'qlnk.net', 'qte.me', 'qu.tc', 'qy.fi', 'r.im', 'rb6.me', 'read.bi', 'readthis.ca', 'reallytinyurl.com', 'redir.ec', 'redirects.ca', 'redirx.com', 'retwt.me', 'ri.ms', 'rickroll.it', 'riz.gd', 'rt.nu', 'ru.ly', 'rubyurl.com', 'rurl.org', 'rww.tw', 's4c.in', 's7y.us', 'safe.mn', 'sameurl.com', 'sdut.us', 'shar.es', 'shink.de', 'shorl.com', 'short.ie', 'short.to', 'shortlinks.co.uk', 'shorturl.com', 'shout.to', 'show.my', 'shrinkify.com', 'shrinkr.com', 'shrt.fr', 'shrt.st', 'shrten.com', 'shrunkin.com', 'simurl.com', 'slate.me', 'smallr.com', 'smsh.me', 'smurl.name', 'sn.im', 'snipr.com', 'snipurl.com', 'snurl.com', 'sp2.ro', 'spedr.com', 'srnk.net', 'srs.li', 'starturl.com', 'su.pr', 'surl.co.uk', 'surl.hu', 't.cn', 't.co', 't.lh.com', 'ta.gd', 'tbd.ly', 'tcrn.ch', 'tgr.me', 'tgr.ph', 'tighturl.com', 'tiniuri.com', 'tiny.cc', 'tiny.ly', 'tiny.pl', 'tinylink.in', 'tinyuri.ca', 'tinyurl.com', 'tl.gd', 'tmi.me', 'tnij.org', 'tnw.to', 'tny.com', 'to.ly', 'togoto.us', 'totc.us', 'toysr.us', 'tpm.ly', 'tr.im', 'tra.kz', 'trunc.it', 'twhub.com', 'twirl.at', 'twitclicks.com', 'twitterurl.net', 'twitterurl.org', 'twiturl.de', 'twurl.cc', 'twurl.nl', 'u.mavrev.com', 'u.nu', 'u76.org', 'ub0.cc', 'ulu.lu', 'updating.me', 'ur1.ca', 'url.az', 'url.co.uk', 'url.ie', 'url360.me', 'url4.eu', 'urlborg.com', 'urlbrief.com', 'urlcover.com', 'urlcut.com', 'urlenco.de', 'urli.nl', 'urls.im', 'urlshorteningservicefortwitter.com', 'urlx.ie', 'urlzen.com', 'usat.ly', 'use.my', 'vb.ly', 'vgn.am', 'vl.am', 'vm.lc', 'w55.de', 'wapo.st', 'wapurl.co.uk', 'wipi.es', 'wp.me', 'x.vu', 'xr.com', 'xrl.in', 'xrl.us', 'xurl.es', 'xurl.jp', 'y.ahoo.it', 'yatuc.com', 'ye.pe', 'yep.it', 'yfrog.com', 'yhoo.it', 'yiyd.com', 'youtu.be', 'yuarel.com', 'z0p.de', 'zi.ma', 'zi.mu', 'zipmyurl.com', 'zud.me', 'zurl.ws', 'zz.gd', 'zzang.kr', '›.ws', '✩.ws', '✿.ws', '❥.ws', '➔.ws', '➞.ws', '➡.ws', '➨.ws', '➯.ws', '➹.ws', '➽.ws'];
}
