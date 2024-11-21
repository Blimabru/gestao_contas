<template>
  <footer class="footer">
    <div class="footer-content">
      <p>&copy; {{ currentYear }} Gestor de Contas. Todos os direitos reservados.</p>
      <div class="social-icons">
        <a href="https://facebook.com" target="_blank" aria-label="Facebook">
          <i class="fa-brands fa-facebook"></i>
        </a>
        <a href="https://x.com" target="_blank" aria-label="Twitter">
          <i class="fa-brands fa-x-twitter"></i>
        </a>
        <a href="https://linkedin.com" target="_blank" aria-label="LinkedIn">
          <i class="fa-brands fa-linkedin"></i>
        </a>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { ref } from 'vue';

// Variável para exibir o ano atual
const currentYear = ref(new Date().getFullYear());
</script>

<style scoped>
/* Rodapé */
.footer {
  background-color: #2c3e50;
  color: #ecf0f1;
  padding: 20px;
  text-align: center;
  position: relative;
  width: 100%;
}  

.footer-content {
  max-width: 1200px;
  margin: 0 auto;
}

.footer p {
  font-size: 1rem;
  margin-bottom: 10px;
}

.social-icons {
  margin-top: 10px;
}

.social-icons a {
  font-size: 1.5rem;
  color: #ecf0f1;
  margin: 0 10px;
  transition: color 0.3s;
}

.social-icons a:hover {
  color: #3498db;
}

/* Responsividade */
@media (max-width: 600px) {
  .footer p {
    font-size: 0.9rem;
  }

  .social-icons a {
    font-size: 1.2rem;
    margin: 0 8px;
  }
}
</style>
