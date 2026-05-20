<?php $session = session(); ?>

<nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="<?= base_url($session->get('dashboardURL')) ?>">
            <img src="<?= base_url('images/logo.png') ?>" class="img-rounded" width="87" height="32" />
        </a>
    </div>

    <!-- TOP MENU -->
    <ul class="nav navbar-top-links navbar-right">
        <?php if (!empty($topMenu)): ?>
            <?php foreach ($topMenu as $item): ?>
                <?php if (!empty($item['links'])): ?>
                    <li class="dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa <?= $item['icon'] ?>"></i>
                            <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-messages">
                            <?php foreach ($item['links'] as $link): ?>

                                <?php if ($link['link_type'] == 1): ?>
                                    <!-- URL interna -->
                                    <li>
                                        <a href="<?= base_url($link['link_url']) ?>">
                                            <i class="fa <?= $link['link_icon'] ?? '' ?> fa-fw"></i>
                                            <?= esc($link['link_name']) ?>
                                        </a>
                                    </li>

                                <?php elseif (in_array($link['link_type'], [2,4,5])): ?>
                                    <!-- URL externa -->
                                    <li>
                                        <a href="<?= $link['link_url'] ?>" target="_blank">
                                            <i class="fa <?= $link['link_icon'] ?? '' ?> fa-fw"></i>
                                            <?= esc($link['link_name']) ?>
                                        </a>
                                    </li>

                                <?php else: ?>
                                    <!-- DIVIDER -->
                                    <li class="divider"></li>

                                <?php endif; ?>

                            <?php endforeach; ?>
                        </ul>
                    </li>
                <?php else: ?>
                    <li>
                        <a href="<?= $item['url'] ?>">
                            <i class="fa <?= $item['icon'] ?>"></i>
                            <?= esc($item['name']) ?>
                        </a>
                    </li>
                <?php endif; ?>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>

    <!-- LEFT MENU -->
    <div class="navbar-default sidebar" role="navigation">
        <div class="sidebar-nav navbar-collapse">
            <ul class="nav" id="side-menu">
                <?php if (!empty($leftMenu)): ?>
                    <?php foreach ($leftMenu as $item): ?>
                        <li>
                            <?php if (!empty($item['url'])): ?>
                                <a href="<?= $item['url'] ?>">
                                    <i class="fa <?= $item['icon'] ?>"></i> <?= esc($item['name']) ?>
                                </a>
                            <?php else: ?>
                                <a href="#">
                                    <i class="fa <?= $item['icon'] ?>"></i> <?= esc($item['name']) ?>
                                    <span class="fa arrow"></span>
                                </a>
                                <?php if (!empty($item['links'])): ?>
                                    <ul class="nav nav-second-level">
                                        <?php foreach ($item['links'] as $link): ?>

                                            <?php if ($link['link_type'] == 1): ?>
                                                <!-- URL interna -->
                                                <li>
                                                    <a href="<?= base_url($link['link_url']) ?>">
                                                        <?= esc($link['link_name']) ?>
                                                    </a>
                                                </li>

                                            <?php elseif (in_array($link['link_type'], [2,4,5])): ?>
                                                <!-- URL externa -->
                                                <li>
                                                    <a href="<?= $link['link_url'] ?>" target="_blank">
                                                        <?= esc($link['link_name']) ?>
                                                    </a>
                                                </li>

                                            <?php else: ?>
                                                <!-- DIVIDER -->
                                                <li class="divider"></li>

                                            <?php endif; ?>

                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>