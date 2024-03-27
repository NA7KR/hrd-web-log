<?php
// backend/footer.php
/*
Copyright © 2024 NA7KR Kevin Roberts. All rights reserved.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
*/
?>
</div>
<div style="text-align: center;">
    <footer>
        <!-- Display the copyright and last modified information -->
        <p>&copy; <?= date("Y"); ?> <?php echo $footertext; ?> </p>
        <p>Last updated: <?= date("F d Y H:i:s.", getlastmod()); ?></p>
    </footer>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const menuToggle = document.querySelector('.menuToggle');
        const header = document.querySelector('header');

        menuToggle.addEventListener('click', function() {
            header.classList.toggle('active');
        });
    });
</script>
</body>
</html>
