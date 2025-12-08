<?php
if (session_status() == PHP_SESSION_NONE) session_start();

$cart = $_SESSION['cart'] ?? [];
$subtotal = 0;
$shipping = 30000;

foreach ($cart as $product) {
    $subtotal += $product['price'] * $product['quantity'];
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Giỏ hàng</title>

<style>
*{margin:0;padding:0;box-sizing:border-box;font-family:Arial}
body{background:#f5f5f5;color:#333}

/* HEADER */
.page-header{padding:20px;text-align:center;margin-bottom:24px}
.step span{background:#eee;padding:8px 12px;border-radius:50%;margin:0 6px;font-weight:600}
.step .active{background:#d60000;color:#fff}

/* LAYOUT */
.cart-container{max-width:1200px;margin:0 auto;display:flex;gap:24px;padding:0 20px}
.product-box{flex:1;background:#fff;padding:20px;border-radius:12px;min-height:220px}
.summary-box{width:340px;background:#fff;padding:22px;border-radius:12px;position:sticky;top:18px;height:max-content}

/* PRODUCT */
.product{display:flex;gap:18px;border-bottom:10px solid #f7f7f7;padding:18px 0;position:relative;align-items:center}
.product img{width:120px;height:120px;object-fit:cover;border-radius:10px;border:1px solid #eee}
.product h3{margin-bottom:6px;font-size:17px}

/* REMOVE BTN */
.btn-remove{
    position:absolute;right:8px;top:8px;width:34px;height:34px;background:#ff4c4c;border-radius:50%;
    color:white;border:none;font-size:18px;cursor:pointer;display:flex;align-items:center;justify-content:center;
    box-shadow:0 6px 18px rgba(0,0,0,0.08);
}

/* QTY */
.qty-box{display:flex;align-items:center;gap:10px;margin-top:8px}
.qty-box button{width:34px;height:34px;border-radius:8px;border:1px solid #ddd;background:white;cursor:pointer}
.qty-box input{width:64px;height:34px;border:1px solid #ddd;border-radius:8px;text-align:center}

/* EMPTY CART (beautified) */
.empty-cart-box { text-align:center;padding:56px 20px; }
.empty-cart-img { width:160px; opacity:.9; margin-bottom:18px; }
.empty-cart-title { font-size:22px; font-weight:700; margin-bottom:8px; color:#222; }
.empty-cart-text { color:#666; margin-bottom:18px; }
.empty-cart-btn { display:inline-block;padding:10px 20px;background:linear-gradient(90deg,#ff6b6b,#d60000);color:#fff;border-radius:10px;text-decoration:none;font-weight:700; }

/* CONFIRM */
.confirm-overlay{position:fixed;inset:0;background:rgba(0,0,0,.45);display:none;align-items:center;justify-content:center;z-index:9999}
.confirm-box{background:#fff;padding:20px;border-radius:12px;width:360px;text-align:center;box-shadow:0 10px 30px rgba(0,0,0,.2)}
.confirm-title{font-size:18px;font-weight:700;margin-bottom:8px}
.confirm-txt{color:#555;margin-bottom:16px}
.confirm-btns{display:flex;justify-content:center;gap:12px}
.btn-confirm-yes{background:#d60000;color:#fff;border:none;padding:10px 18px;border-radius:8px;cursor:pointer}
.btn-confirm-no{background:#f0f0f0;border:none;padding:10px 18px;border-radius:8px;cursor:pointer}

/* FADE OUT */
.fade-out{opacity:0;transform:translateX(-16px);transition:all .28s ease}

/* TOAST */
#toast{position:fixed;right:20px;bottom:20px;background:#222;color:#fff;padding:10px 16px;border-radius:8px;opacity:0;transform:translateY(6px);transition:all .28s}
#toast.show{opacity:1;transform:translateY(0)}
</style>
</head>
<body>

<div class="page-header">
  <div class="step">
    <span class="active">1</span> Giỏ hàng &nbsp; › &nbsp;
    <span>2</span> Thanh toán &nbsp; › &nbsp;
    <span>3</span> Hoàn tất
  </div>
</div>

<div class="cart-container">

  <div class="product-box" id="productBox">

    <!-- EMPTY CART -->
    <div id="empty-cart" class="empty-cart-box" style="<?= empty($cart) ? '' : 'display:none;' ?>">
      <img src="https://cdn-icons-png.flaticon.com/512/2038/2038854.png" class="empty-cart-img" alt="empty">
      <div class="empty-cart-title">Giỏ hàng trống</div>
      <div class="empty-cart-text">Bạn chưa có sản phẩm nào trong giỏ. Thêm vài món thôi nào!</div>
      <a href="index.php?page=product" class="empty-cart-btn">Tiếp tục mua sắm</a>
    </div>

    <!-- PRODUCTS -->
    <?php if (!empty($cart)): ?>
      <?php foreach ($cart as $id => $product): ?>
        <div class="product" data-id="<?= $id ?>" data-price="<?= $product['price'] ?>">
          <img src="App/public/img/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
          <div style="flex:1">
            <h3><?= htmlspecialchars($product['name']) ?></h3>
            <div>Size: <strong><?= htmlspecialchars($product['size']) ?></strong></div>
            <div style="margin-top:6px">Giá: <strong><?= number_format($product['price']) ?> ₫</strong></div>

            <div class="qty-box">
              <button class="qty-minus" aria-label="minus">−</button>
              <input type="number" class="qty-input" min="1" value="<?= intval($product['quantity']) ?>">
              <button class="qty-plus" aria-label="plus">+</button>
            </div>
          </div>

          <button class="btn-remove" title="Xóa sản phẩm">×</button>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

  </div> <!-- /product-box -->

  <div class="summary-box" id="summaryBox" style="<?= empty($cart) ? 'display:none;' : '' ?>">
    <h3 style="margin-bottom:10px">Tổng cộng</h3>
    <p style="display:flex;justify-content:space-between"><span>Tạm tính</span><b id="subtotal"><?= number_format($subtotal) ?> ₫</b></p>
    <p style="display:flex;justify-content:space-between"><span>Vận chuyển</span><b><?= number_format($shipping) ?> ₫</b></p>
    <hr style="border:none;border-top:1px dashed #eee;margin:12px 0">
    <p style="display:flex;justify-content:space-between;font-size:18px;color:#d60000;font-weight:700"><span>Tổng</span><span id="total"><?= number_format($subtotal + $shipping) ?> ₫</span></p>
    <a href="index.php?page=order" class="btn-checkout" style="display:block;text-align:center;margin-top:14px">Thanh toán</a>
    <a href="index.php?page=product" class="btn-ttms" style="display:block;text-align:center;margin-top:14px">Tiếp tục mua sắm</a>
  </div>

</div> <!-- /cart-container -->

<!-- CONFIRM -->
<div class="confirm-overlay" id="confirmOverlay" aria-hidden="true">
  <div class="confirm-box" role="dialog" aria-modal="true">
    <div class="confirm-title">Xóa sản phẩm?</div>
    <div class="confirm-txt">Bạn có chắc muốn xóa sản phẩm này khỏi giỏ hàng?</div>
    <div class="confirm-btns">
      <button class="btn-confirm-yes" id="confirmYes">Xóa</button>
      <button class="btn-confirm-no" id="confirmNo">Hủy</button>
    </div>
  </div>
</div>

<div id="toast" role="status" aria-live="polite"></div>

<script>
// ---- config
const shipping = <?= json_encode($shipping) ?>;

// ---- DOM refs
const productBox = document.getElementById('productBox');
const summaryBox = document.getElementById('summaryBox');
const emptyCart = document.getElementById('empty-cart');
const confirmOverlay = document.getElementById('confirmOverlay');
const confirmYes = document.getElementById('confirmYes');
const confirmNo = document.getElementById('confirmNo');
const toast = document.getElementById('toast');

// item pending to delete
let pendingDeleteItem = null;

// show toast
function showToast(text){
  toast.textContent = text;
  toast.classList.add('show');
  setTimeout(()=> toast.classList.remove('show'), 1500);
}

// compute totals and show/hide summary or empty state
function updateTotals(){
  const products = productBox.querySelectorAll('.product');
  let subtotal = 0;
  if (products.length === 0) {
    // empty
    summaryBox.style.display = 'none';
    emptyCart.style.display = 'block';
    return;
  }
  products.forEach(p=>{
    const price = parseInt(p.dataset.price) || 0;
    const qty = parseInt(p.querySelector('.qty-input').value) || 1;
    subtotal += price * qty;
  });
  document.getElementById('subtotal').textContent = subtotal.toLocaleString() + ' ₫';
  document.getElementById('total').textContent = (subtotal + parseInt(shipping)).toLocaleString() + ' ₫';
  summaryBox.style.display = 'block';
  emptyCart.style.display = 'none';
}

// helper: POST form urlencoded
function post(url, data){
  const body = Object.keys(data).map(k=> encodeURIComponent(k)+'='+encodeURIComponent(data[k])).join('&');
  return fetch(url, {
    method: 'POST',
    headers: {'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'},
    body
  });
}

// attach handlers to current product nodes
function attachProductHandlers(){
  // remove existing to avoid double-binding
  const products = productBox.querySelectorAll('.product');
  products.forEach(p=>{
    // if already has a bound flag, skip (we'll remove and rebind for simplicity)
    // remove previous listeners by cloning node
    const clone = p.cloneNode(true);
    p.parentNode.replaceChild(clone, p);
  });

  // now attach
  const freshProducts = productBox.querySelectorAll('.product');
  freshProducts.forEach(p=>{
    const id = p.dataset.id;
    const minus = p.querySelector('.qty-minus');
    const plus = p.querySelector('.qty-plus');
    const input = p.querySelector('.qty-input');
    const btnRemove = p.querySelector('.btn-remove');

    if(minus){
      minus.addEventListener('click', ()=>{
        let v = parseInt(input.value) || 1;
        if(v > 1) v--;
        input.value = v;
        updateCartOnServer(id, v);
        updateTotals();
      });
    }

    if(plus){
      plus.addEventListener('click', ()=>{
        let v = parseInt(input.value) || 1;
        v++;
        input.value = v;
        updateCartOnServer(id, v);
        updateTotals();
      });
    }

    if(input){
      input.addEventListener('change', ()=>{
        let v = parseInt(input.value) || 1;
        if(v < 1) v = 1;
        input.value = v;
        updateCartOnServer(id, v);
        updateTotals();
      });
    }

    if(btnRemove){
      btnRemove.addEventListener('click', ()=>{
        pendingDeleteItem = p;
        confirmOverlay.style.display = 'flex';
        confirmOverlay.setAttribute('aria-hidden','false');
      });
    }
  });
}

// server calls
function updateCartOnServer(id, quantity){
  // posts to controller handling update; server should update $_SESSION accordingly
  post('index.php?page=giohang&action=update', { id, quantity })
  .catch(err=> console.error('updateCartOnServer error', err));
}

function removeFromServer(id){
  return post('index.php?page=giohang&action=remove', { id });
}

// confirm handlers
confirmYes.addEventListener('click', ()=>{
  if(!pendingDeleteItem){
    confirmOverlay.style.display = 'none';
    confirmOverlay.setAttribute('aria-hidden','true');
    return;
  }
  const id = pendingDeleteItem.dataset.id;
  // visual effect
  pendingDeleteItem.classList.add('fade-out');

  // ensure server removal then remove from DOM
  removeFromServer(id)
  .then(()=> {
    // remove DOM after animation
    setTimeout(()=>{
      if(pendingDeleteItem && pendingDeleteItem.parentNode){
        pendingDeleteItem.parentNode.removeChild(pendingDeleteItem);
      }
      pendingDeleteItem = null;
      attachProductHandlers(); // rebind handlers
      updateTotals();
      showToast('Đã xóa sản phẩm');
    }, 280);
  })
  .catch(err=>{
    console.error(err);
    showToast('Xóa không thành công');
    pendingDeleteItem.classList.remove('fade-out');
    pendingDeleteItem = null;
  })
  .finally(()=>{
    confirmOverlay.style.display = 'none';
    confirmOverlay.setAttribute('aria-hidden','true');
  });
});

confirmNo.addEventListener('click', ()=>{
  pendingDeleteItem = null;
  confirmOverlay.style.display = 'none';
  confirmOverlay.setAttribute('aria-hidden','true');
});

// initial bind + totals
attachProductHandlers();
updateTotals();
</script>
</body>
</html>
