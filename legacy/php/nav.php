<?php

// A port from original nav setup code

if (!function_exists('navMenu')) {
    function navMenu($v)
    {
        $mainMenuDataJson = file_get_contents(base_path('new/data/pages.json'));
        $mainMenuData = json_decode($mainMenuDataJson, true);
        $v['_nav'] = '';

        foreach ($mainMenuData as $count => $d) {

            if ($v['_language'] === 'Thai') {
                $title = $d['titleTh'];
                $eHref = e('/th/' . $d['slug']);
            } else {
                $title = $d['titleEn'];
                $eHref = e('/' . $d['slug']);
            }
            $eTitle = e($title);
            $eSlug = e($d['slug']);

            if ($v['_page'] === $d['slug']) {

                $active = 'active';
                $v['_page_title'] = $title;
                $v['_type'] = $d['displayType'];

                if (($d['displayType'] === 'Table' && $v['_subpage'] != '')) {
                    $v['_type'] = 'Entry';
                    $v['_action'] = 'entry';
                    $v['_entry'] = $v['_subpage'];
                } else if ($v['_page'] === 'gallery' && $v['_subpage'] != '') {
                    $v['_type'] = 'Album';
                    $v['_action'] = 'album';
                    $v['_album'] = $v['_subpage'];
                } else if ($v['_page'] === 'calendar' && $v['_subpage'] != '') {
                    $v['_type'] = 'Event';
                    $v['_action'] = 'event';
                    $v['_event'] = $v['_subpage'];
                } else if ($v['_subpage'] === 'residents' && $v['_subsubpage'] != '') {
                    $v['_page'] = 'Residents';
                    $v['_subpage'] = $v['_subsubpage'];
                    $v['_type'] = 'Resident';
                    $v['_action'] = 'resident';
                    $v['_resident'] = $v['_subsubpage'];
                } else if ($v['_subpage'] != '') {
                    $v['_action'] = 'subpage';
                }
                $v['_icon'] = $d['oldIcon'];
                $v['_meta_description'] = $d['descriptionEn'];

            } else {
                $active = '';
            }

            if (substr($d['slug'], 0, 4) === 'new/') {
                $buttonId = 'btn-' . e(substr($d['slug'], 4));
                $onclick = '';
            } else {
                $buttonId = 'btn-' . $eSlug;
                $onclick = 'onclick="nav(\'' . $eSlug . '\');return false;"';
            }

            $v['_nav'] .= <<<EOT
                <div class="brick">
                    <a href="$eHref" {$onclick}>
                        <div id="{$buttonId}" class="btn-nav {$active}">
                            <i class="{$d['cssClass']} icon {$d['oldIcon']}"></i><br>
                            <span class="{$d['cssClass']} title-icon">{$eTitle}</span>
                        </div>
                    </a>
                </div>
EOT;
        }
        return $v;
    }
}

extract(navMenu(compact('_page', '_subpage', '_subsubpage', '_language')));
