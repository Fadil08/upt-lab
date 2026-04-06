<?php
/**
 * @Created by          : Waris Agung Widodo (ido.alit@gmail.com)
 * @Date                : 2019-01-29 23:33
 * @File name           : vegas.js.php
 */

if (!$sysconf['template']['classic_library_disableslide']):
?>
<script>
  $('.c-header, .vegas-slide').vegas({
        delay: <?= $sysconf['template']['classic_slide_delay']; ?>,
        timer: false,
        transition: '<?= $sysconf['template']['classic_slide_transition']; ?>',
        animation: '<?= $sysconf['template']['classic_slide_animation']; ?>',
        slides: [
            { src: "<?php echo CURRENT_TEMPLATE_DIR . v('assets/images/slide1.jpeg'); ?>" },
            { src: "<?php echo CURRENT_TEMPLATE_DIR . v('assets/images/slide2.jpeg'); ?>" },
            { src: "<?php echo CURRENT_TEMPLATE_DIR . v('assets/images/slide3.jpeg'); ?>" },
			{ src: "<?php echo CURRENT_TEMPLATE_DIR . v('assets/images/slide4.jpeg'); ?>" },
            { src: "<?php echo CURRENT_TEMPLATE_DIR . v('assets/images/slide5.jpeg'); ?>" }
        ]
    });
</script>
<script>
$(document).ready(function () {
  // HANYA halaman visitor
  if (new URLSearchParams(window.location.search).get('p') === 'visitor') {

    $('#visitor-bg').vegas({
      delay: 5000,               // durasi per gambar (ms)
      timer: false,
      shuffle: true,
      transition: 'fade2',       // transisi halus
      transitionDuration: 2000,  // durasi transisi
      animation: 'kenburnsUp',   // efek zoom elegan
      slides: [
        { src: 'template/default/assets/images/visitor1.jpg' },
        { src: 'template/default/assets/images/visitor2.jpg' },
        { src: 'template/default/assets/images/visitor3.jpg' }
      ]
    });

  }
});
</script>


<?php
endif;