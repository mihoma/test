<?php get_header(); ?>


<div id="container" class="wrapper pad">
    <div id="main">

        <?php if( !is_tag()){ ?>
        <div id="sidebar">
            <?php
            $thisCat = get_category($cat); //現在表示しているカテゴリー情報を取得
            //var_dump($cat);
            //var_dump($thisCat->parent);
            if( $thisCat->parent == 0 ){
                $targetCat = $cat;
            }else{
                $targetCat = $thisCat->parent;
            }

            $args = array(
                'parent' => $targetCat //現在のカテゴリーの直近子カテゴリーを取得
            );
            $catChildren = get_categories( $args ); //上記の条件でカテゴリー情報を取得
            if($catChildren){ //子カテゴリーがある場合、子カテゴリーを表示する
                echo '<ul>';
                foreach($catChildren as $catChild){
                    echo '<li><a href="'. get_category_link($catChild->term_id). '">'. $catChild -> name. '</a></li>';
                }
                echo '</ul>';
            }
            ?>
        </div>
        <?php } ?>


        <div id="main" class="<?php if( is_tag()){ echo 'wide';} ?>">

            <?php if (have_posts()) : ?>
            <?php $post = $posts[0]; // Hack. Set $post so that the_date() works. ?>
            <?php /* If this is a category archive */ if (is_category()) { ?>

            <h1 class="pagetitle"><?php
                $cat = get_the_category();
                /*
                if($cat[0]->parent){
                    $parent = get_category($cat[0]->parent);
                    echo attribute_escape($parent->cat_name);
                }else{
                    $cat = $cat[0];
                    echo $cat->cat_name; 
                }
                */
                $cat = $cat[0];
                echo $cat->cat_name; 
            ?></h1>
            <?php /* If this is a tag archive */ } elseif( is_tag() ) { ?>
            <h1 class="pagetitle"><?php printf(__('Posts Tagged &#8216;%s&#8217;', 'kubrick'), single_tag_title('', false) ); ?></h1>
            <?php /* If this is a daily archive */ } elseif (is_day()) { ?>
            <h1 class="pagetitle"><?php printf(_c(' %s|Daily archive page', 'kubrick'), get_the_time(__('F jS, Y', 'kubrick'))); ?>年</h1>
            <?php /* If this is a monthly archive */ } elseif (is_month()) { ?>
            <h1 class="pagetitle"><?php printf(_c(' %s|Monthly archive page', 'kubrick'), get_the_time(__('F, Y', 'kubrick'))); ?>年</h1>
            <?php /* If this is a yearly archive */ } elseif (is_year()) { ?>
            <h1 class="pagetitle"><?php printf(_c('Archive for %s|Yearly archive page', 'kubrick'), get_the_time(__('Y', 'kubrick'))); ?></h1>
            <?php /* If this is an author archive */ } elseif (is_author()) { ?>
            <h1 class="pagetitle"><?php _e('Author Archive', 'kubrick'); ?></h1>
            <?php /* If this is a paged archive */ } elseif (isset($_GET['paged']) && !empty($_GET['paged'])) { ?>
            <h1 class="pagetitle"><?php _e('Blog Archives', 'kubrick'); ?></h1>
            <?php } ?>


            <?php while (have_posts()) : the_post(); ?>
                <div class="newsBlock">

                    <?php /* the_content() */ ?>
                    <?php
                    if( in_category( 4 ) ){

                        echo the_date('Y/m/d');

                        $seminar_date = get_field(seminar_date);
                        $seminar_target = get_field(seminar_target);
                        $seminar_where = get_field(seminar_where);
                        echo '<table class="grid">';
                        echo '<tr class="date"><th>主催</th><td>' . $post->post_title . '</td></tr>';
                        echo '<tr class="date"><th>テーマ</th><td>' . $post->post_content . '</td></tr>';
                        if( $seminar_date ){
                            echo '<tr class="date"><th>日時</th><td>' .$seminar_date. '</td></tr>';
                        };
                        if( $seminar_target ){
                            echo '<tr class="target"><th>対象</th><td>' .$seminar_target. '</td></tr>';
                        };
                        if( $seminar_where ){
                            echo '<tr class="where"><th>場所</th><td>' .$seminar_where. '</td></tr>';
                        };
                        echo '</table>';


                    }else{
                        //echo '<h2 id="post-' . get_the_ID() .'" class="newsContents"><a class="link" href="'. get_the_permalink() .'">'. get_the_title(). '</a></h2>';
                        echo '<h2 id="post-' . get_the_ID() .'" class="newsContents">'. get_the_title(). '</h2>';
                        echo the_date('Y/m/d');
                        the_content();
                        $relation_url = get_field(relation_url);
                        if( $relation_url ){
                            echo '<div class="url"><a href="' .$relation_url. '">' .$relation_url. '</a></div>';
                        }
                        the_tags('<ul class="tags"><li>','</li><li>','</li></ul>');
                    }
                    /*
                    $my_content = get_the_content(); //コンテンツ取得
                    $my_content = preg_replace("|(<img[^>]+>)|si","",$my_content); //イメージ要素をのぞく
                    $my_content = wpautop($my_content); //br p を調整
                    $my_content = strip_tags($my_content); //タグをのぞく

                    echo '<div class="newsContents">'. nl2br( mb_strimwidth( $my_content, 0, 90) ) .'…</div>';
                    */
                    ?>
                </div>
            <?php endwhile; ?>

            <ul class="pageNav">
                <li class="prev"><?php previous_posts_link('&lt;'); ?></li>
                <li class="next"><?php next_posts_link('&gt;'); ?></li>
            </ul>

            <?php else :
            if ( is_category() ) { // If this is a category archive
                printf("<h2 class='center'>".__("Sorry, but there aren't any posts in the %s category yet.", 'kubrick').'</h2>', single_cat_title('',false));
            } else if ( is_date() ) { // If this is a date archive
                echo('<h2>'.__("Sorry, but there aren't any posts with this date.", 'kubrick').'</h2>');
            } else if ( is_author() ) { // If this is a category archive
                $userdata = get_userdatabylogin(get_query_var('author_name'));
                printf("<h2 class='center'>".__("Sorry, but there aren't any posts by %s yet.", 'kubrick')."</h2>", $userdata->display_name);
            } else {
                echo("<h2 class='center'>".__('No posts found.', 'kubrick').'</h2>');
            }
            get_search_form();
            endif;
        ?>
    </div>

    </div>

</div>

<?php get_footer(); ?>