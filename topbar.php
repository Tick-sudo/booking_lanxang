<?php
session_start();
?>
<div class="topbar">
    <div class="logo">ສາຍການບິນລ້ານຊ້າງ</div>
    <nav>
        <a href="#">ໜ້າຫຼັກ</a>
        <a href="#">ຂໍ້ມູນການບິນ</a>
        <a href="booking_history.php" class="btn btn-primary">ປະຫວັດການຈອງ</a>
        <a href="#">ກ່ຽວກັບພວກເຮົາ</a>

        <div class="dropdown" style="display:inline-block; position:relative;">
            <a href="#" class="profile-icon" onclick="toggleProfileMenu(event)">
                <img src="https://cdn-icons-png.flaticon.com/512/149/149071.png" 
                     alt="Profile" 
                     style="width:30px; height:30px; border-radius:50%; vertical-align:middle;">
            </a>
            <div class="dropdown-menu" id="profileMenu" 
                 style="display:none; position:absolute; right:0; top:40px; background:#fff; border:1px solid #ccc; border-radius:5px; min-width:200px; box-shadow:0 2px 6px rgba(0,0,0,0.2); z-index:100;">
                 
                <?php if (isset($_SESSION['customer_email'])): ?>
                    <div style="padding:10px; border-bottom:1px solid #eee; font-size:14px;">
                        <strong>ກຳລັງລ໋ອກອິນ:</strong><br>
                        <?php echo htmlspecialchars($_SESSION['customer_email']); ?>
                    </div>
                    <a href="logout.php" style="display:block; padding:10px; text-decoration:none; color:#333;">
                        ອອກຈາກລະບົບ
                    </a>
                <?php else: ?>
                    <a href="login.php" style="display:block; padding:10px; text-decoration:none; color:#333;">
                        ເຂົ້າສູ່ລະບົບ
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>
</div>

<script>
function toggleProfileMenu(event) {
    event.preventDefault();
    var menu = document.getElementById('profileMenu');
    menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
}
</script>
