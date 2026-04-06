<style>
    @media only screen and (max-width: 600px) {
        video {
            width: 800px !important;
            height: 450px !important;
        }
    }

    .card-body {
        padding: 1.55rem;
    }

    #slims-home {
        background: white;
        position: relative;
        top: -76px;
    }

    #slims-home > .container > h4 {
        padding-top: 49px;
    }

    #outercontainer {
        position: relative;
        background: white;
        /* top: -40px; */
        padding-top: 50px;
        min-height: 100vh;
    }

    .search {
        position: absolute;
        z-index: 5;
        width: 100%;
    <?php if (isset($_GET['search']) || isset($_GET['p'])): ?>
        margin-top: -11px;
        top: 127px;
        margin-left: auto;
        margin-right: auto;
        display: block;
    <?php else: ?>
        top: 430px;
    <?php endif; ?>
    }

    #member_sidebar {
        min-height: 100vh;
    }
</style>
<video autoplay muted loop id="myVideo" style="width: 100%;position: absolute;">
    <source src="<?= SWB ?>template/<?= basename(dirname(__DIR__)) ?>/assets/videos/videos1.mp4" type="video/mp4">
</video>
