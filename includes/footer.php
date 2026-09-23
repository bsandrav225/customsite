  </main>

  <footer>
    <div class="footer-inner">
      <div>
        <div class="footer-logo">CRAFT<span>D</span></div>
        <p class="footer-desc">
          Ручная кастомизация одежды и вещей. Не магазин готовых футболок —
          студия, где из вашей идеи получается единственная вещь.
        </p>
        <?php $socials = site_socials(); ?>
        <?php if ($socials): ?>
          <div class="social-links">
            <?php foreach ($socials as $name => $item): ?>
              <a class="social-btn" href="<?= h($item['url']) ?>" target="_blank" rel="noopener noreferrer" aria-label="<?= h($item['label']) ?>">
                <?= icon($name, 18) ?>
              </a>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
      </div>

      <div class="footer-col">
        <h4>Навигация</h4>
        <ul>
          <li><a href="index.php">Главная</a></li>
          <li><a href="works.php">Работы</a></li>
          <li><a href="customize.php">Что можно кастомизировать</a></li>
          <li><a href="how.php">Как это работает</a></li>
          <li><a href="about.php">О мастере</a></li>
          <li><a href="prices.php">Стоимость</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Заказ</h4>
        <ul>
          <li><a href="order.php">Заказать кастомизацию</a></li>
          <li><a href="faq.php">FAQ</a></li>
          <li><a href="contacts.php">Контакты</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4>Документы</h4>
        <ul>
          <li><a href="privacy.php">Политика конфиденциальности</a></li>
          <li><a href="consent.php">Обработка персональных данных</a></li>
        </ul>
      </div>
    </div>

    <div class="footer-bottom">
      <p>© <?= date('Y') ?> CRAFTD. Ручная кастомизация.</p>
      <p class="footer-note">Каждая вещь собирается индивидуально.</p>
    </div>
  </footer>

  <script src="main.js"></script>
</body>
</html>
