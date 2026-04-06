<?php
/**
 * @Created by          : Waris Agung Widodo (ido.alit@gmail.com)
 * @Date                : 2020-01-02 16:27
 * @File name           : _modal_topic.php
 */

?>

 <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"><?=  __('Media Partner'); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <ul class="topic d-flex flex-wrap justify-content-center p-0">
                    <li class="d-flex justify-content-center align-items-center m-2">
                        <a target="_blank"href="https://perpenas-banyuwangi.or.id/" class="d-flex flex-column">
							<img src="<?php echo assets('images/perpenas.png'); ?>" width="150" class="mb-3 mx-auto"/>
							<?php echo __('Perpenas Banyuwangi'); ?>
						</a>
                    </li>
                    <li class="d-flex justify-content-center align-items-center m-2">
                        <a target="_blank"href="https://untag-banyuwangi.ac.id" class="d-flex flex-column">
							<img src="<?php echo assets('images/UNTAG.png'); ?>" width="75" class="mb-3 mx-auto"/>
							<?php echo __('Untag Banyuwangi'); ?>
						</a>
                    </li>
                    <li class="d-flex justify-content-center align-items-center m-2">
                        <a target="_blank"href="https://fh.untag-banyuwangi.ac.id" class="d-flex flex-column">
							<img src="<?php echo assets('images/FH.png'); ?>" width="75" class="mb-3 mx-auto"/>
							<?php echo __('Fakultas Hukum'); ?>
						</a>
                    </li>
                    <li class="d-flex justify-content-center align-items-center m-2">
                        <a target="_blank"href="https://fisip.untag-banyuwangi.ac.id" class="d-flex flex-column">
							<img src="<?php echo assets('images/FISIP.png'); ?>" width="75" class="mb-3 mx-auto"/>
							<?php echo __('Fak. Ilmu Sosial & Ilmu Politik'); ?>
						</a>
                    </li>
                    <li class="d-flex justify-content-center align-items-center m-2">
                        <a target="_blank"href="https://fe.untag-banyuwangi.ac.id" class="d-flex flex-column">
							<img src="<?php echo assets('images/FE.png'); ?>" width="75" class="mb-3 mx-auto"/>
							<?php echo __('Fakultas Ekonomi'); ?>
						</a>
                    </li>
                    <li class="d-flex justify-content-center align-items-center m-2">
                        <a target="_blank"href="https://fpp.untag-banyuwangi.ac.id" class="d-flex flex-column">
							<img src="<?php echo assets('images/FAPERTA.png'); ?>" width="75" class="mb-3 mx-auto"/>
							<?php echo __('Fak. Pertanian & Perikanan'); ?>
						</a>
                    </li>
                    <li class="d-flex justify-content-center align-items-center m-2">
                        <a target="_blank"href="https://fkip.untag-banyuwangi.ac.id" class="d-flex flex-column">
							<img src="<?php echo assets('images/FKIP.png'); ?>" width="75" class="mb-3 mx-auto"/>
							<?php echo __('Fak. Keguruan & Ilmu Pendidikan'); ?>
						</a>
                    </li>
                    <li class="d-flex justify-content-center align-items-center m-2">
                        <a target="_blank"href="https://ft.untag-banyuwangi.ac.id" class="d-flex flex-column">
							<img src="<?php echo assets('images/FT.png'); ?>" width="75" class="mb-3 mx-auto"/>
							<?php echo __('Fakultas Teknik'); ?>
						</a>                   
                    </li>
                </ul>
            </div>
            <div class="modal-footer text-muted text-sm">
                <div>Logo From by <a href="http://www.untag-banyuwangi.ac.id" title="Freepik">untag banyuwangi</a> </div>
            </div>
        </div>
    </div>
</div>
