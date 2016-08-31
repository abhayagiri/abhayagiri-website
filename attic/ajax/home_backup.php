<?php *
            $stmt = $func->google_calendar();
            foreach ($stmt->entry as $event) {
                $title = $event->title;
                $namespaces = $event->getNameSpaces(true);
                $gd = $event->children($namespaces['gd']);
                $date = $gd->when->attributes();
                $starttime = date("F j, Y", strtotime($date['startTime']));
                $id = str_replace('https://www.google.com/calendar/feeds/abhayagiri.org_2tr1cpnhbe4i5cria1l6ae8si8%40group.calendar.google.com/public/full/', '', $event->id);
                $id = str_replace('http://www.google.com/calendar/feeds/abhayagiri.org_2tr1cpnhbe4i5cria1l6ae8si8%40group.calendar.google.com/public/full/', '', $id);
               */ ?>
                <!-- <p> <a class="title-event" href="/calendar" onclick="navEvent(' --> <?= //$id ?> <!--');
                            return false;">--> <?= //$title ?>
                    <!--</a><br>-->
                    <?= //$starttime ?>
                <!--</p> -->
            <?php //} ?>
            <!--<p>
                <a class="btn viewmore" href="/calendar" onclick="nav('calendar');
                        return false;">
                    <i class="icon-share-alt"></i>
                    View Full Calendar
                </a>
            </p>-->