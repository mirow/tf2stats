<table cellpadding=0 align="right" cellspacing=0 class=nav_table width="200">
    <tr>
        <td class=row><?php
            if ($page > 1) {
                echo '<a href="?page=' . $prevpage . '&amp;ppp=' . $ppp . '&playername=' . $playername . '">&lt; prev</a>';
            } else {
                echo '<FONT COLOR="#A0A0A0">&lt; prev</FONT>';
            }
            ?>
        </td>
        <td class=row_alt>
            <?php echo $page . " / " . $lastpage; ?>
        </td>
        <td class=row>
            <?php
            if (count($foundPlayers) == $ppp) {
                echo '<a href="?page=' . $nextpage . '&amp;ppp=' . $ppp . '&playername=' . $playername . '">next &gt;</a>';
            } else {
                echo '<FONT COLOR="#A0A0A0">next &gt;</FONT>';
            }
            ?>
        </td>
    </tr>
    <?php
    if ($nos > $minpaging) {
        ?>
        <tr>
            <td class=row colspan="2">
                <center>
                    <?php
                    $first = true;
                    $middle = $page - floor($maxpaging / 2);
                    if ($middle < 1) {
                        $middle = 1;
                    } else {
                        if (($page + floor($maxpaging / 2)) >= $nos) {
                            $middle = $nos - $maxpaging;
                        }
                    }

                    for ($i = $middle; $i <= $nos; $i++) {
                        if (($i - $middle) <= $maxpaging) {
                            if ($i == $page) {
                                if ($first && $i <> 1) {
                                    echo '<a href="?page=1&amp;ppp=' . $ppp . '&playername=' . $playername . '&nos=' . $nos . '" > 1 </a> ...';
                                }
                                echo "<strong> " . $i . "</strong>";

                                $first = false;
                            } else {
                                if ($first && $i <> 1) {
                                    echo '<a href="?page=1&amp;ppp=' . $ppp . '&playername=' . $playername . '&nos=' . $nos . '" > 1 </a> ...';
                                }

                                echo '<a href="?page=' . $i . '&amp;ppp=' . $ppp . '&playername=' . $playername . '&nos=' . $nos . '" > ' . $i . ' </a>';
                                $first = false;
                            }
                        } else {
                            if ($i == $nos) {
                                //last page
                                if ($i == $page) {
                                    if ($first && $i <> 1) {
                                        echo '<a href="?page=1&amp;ppp=' . $ppp . '&playername=' . $playername . '&nos=' . $nos . '" > 1 </a> ...';
                                    }
                                    echo " ... <strong> " . $i . "</strong>";
                                    $first = false;
                                } else {
                                    if ($first && $i <> 1) {
                                        echo '<a href="?page=1&amp;ppp=' . $ppp . '&playername=' . $playername . '&nos=' . $nos . '" > 1 </a> ...';
                                    }
                                    echo ' ... <a href="?page=' . $i . '&amp;ppp=' . $ppp . '&playername=' . $playername . '&nos=' . $nos . '" > ' . $i . ' </a>';
                                    $first = false;
                                }
                            }
                        }
                    }
                    ?>

                </CENTER>
            </td>
            <td class=row style="color:#000;">
                <form method="get" action="#">
                    <input type="text" width="15px" style="width:15px;" value="<?php echo $page; ?>" name="page"/>
                    <input type="submit" class="btn" value="GO" name="gotopagebtn"/>
                </form>
            </td>

        </tr>

    <?php
    }
    ?>

</table>