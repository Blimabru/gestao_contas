<script>
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

export default {
  components: {
    Link,
  },
  data() {
    return {
      formMode: 'create',
      formData: {
        description: '',
        value: null,
        due_date: '',
        status: 'Pendente',
        type: 'Pagar',
      },
      loading: false,
      financeId: null,
      finances: {
        data: [],
      },
      sortBy: 'due_date',
      sortOrder: 'desc',
      filterStatus: 'Todos',
    };
  },
  methods: {
    formatValue(event) {
      let value = event.target.value.replace(/\D/g, ''); // Remove todos os caracteres não numéricos
      value = (value / 100).toFixed(2).replace('.', ','); // Adiciona a vírgula como separador decimal

      // Formata com ponto como separador de milhar
      const parts = value.split(',');
      const integerPart = parts[0];
      const decimalPart = parts[1] ? ',' + parts[1] : '';

      const formattedValue = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.') + decimalPart;
      event.target.value = formattedValue;
      this.formData.value = formattedValue;
    },
    formatCurrency(value) {
      return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
    },
    formatDate(dataHora) {
      const createdAt = new Date(dataHora);
      return createdAt.toLocaleDateString('pt-BR');
    },
    validateForm() {
      if (!this.formData.description) {
        alert("Descrição é obrigatória!");
        return false;
      }
      if (!this.formData.value || this.formData.value <= 0) {
        alert("O valor deve ser maior que 0!");
        return false;
      }
      if (!this.formData.due_date) {
        alert("Data de vencimento é obrigatória!");
        return false;
      }
      if (!["Pendente", "Pago", "Atrasado"].includes(this.formData.status)) {
        alert("Status inválido!");
        return false;
      }
      if (!["Pagar", "Receber"].includes(this.formData.type)) {
        alert("Tipo inválido!");
        return false;
      }
      return true;
    },

    submitForm() {
      this.formData.user_id = this.$page.props.auth.user.id;

      // Convertendo o valor para número
      const numericValue = parseFloat(String(this.formData.value).replace(/\./g, '').replace(',', '.'));

      this.formData.value = numericValue;

      if (this.validateForm()) {
        if (this.formMode === "create") {
          this.createFinance();
        } else {
          this.updateFinance();
        }
      }
    },
    createFinance() {
      this.loading = true;
      axios
        .post('http://localhost:8000/api/finances', this.formData, { withCredentials: true })
        .then(() => {
          alert('Conta criada com sucesso!');
          this.fetchFinances();
          this.resetForm();
        })
        .catch(error => {
          console.log('Requisição ==> http://localhost:8000/api/finances', this.formData);
          console.error('Erro ao criar a conta', error);
          alert('Erro ao criar a conta');
        })
        .finally(() => {
          this.loading = false;
        });
    },
    updateFinance() {
      this.loading = true;
      axios
        .put(`http://localhost:8000/api/finances/${this.financeId}`, this.formData)
        .then(response => {
          alert('Conta atualizada com sucesso!');
          this.fetchFinances();
          this.resetForm();
        })
        .catch(error => {
          console.error("Erro ao atualizar conta:", error);
        })
        .finally(() => {
          this.loading = false;
        });
    },
    fetchFinances() {
      this.loading = true;
      const params = {
        sortBy: this.sortBy,
        sortOrder: this.sortOrder,
        filterStatus: this.filterStatus,
      };

      axios
        .get('http://localhost:8000/api/finances', { params, withCredentials: true })
        .then(response => {
          this.finances = response.data;
        })
        .catch(error => {
          console.error("Erro ao buscar finanças:", error);
        })
        .finally(() => {
          this.loading = false;
        });
    },
    deleteFinance(id) {
      if (confirm('Tem certeza que deseja excluir esta conta?')) {
        this.loading = true;
        axios
          .delete(`http://localhost:8000/api/finances/${id}`, { withCredentials: true })
          .then(() => {
            alert('Conta excluída com sucesso!');
            this.fetchFinances();
          })
          .catch(error => {
            console.error('Erro ao excluir a conta', error);
            alert('Erro ao excluir a conta');
          })
          .finally(() => {
            this.loading = false;
          });
      }
    },
    resetForm() {
      this.formData = {
        description: '',
        value: null,
        due_date: '',
        status: 'Pendente',
        type: 'Pagar',
      };
      this.formMode = 'create';
      this.financeId = null;
    },
    setEditMode(finance) {
      this.formMode = 'edit';
      this.financeId = finance.id;
      this.formData = { ...finance };

      // Rolar até o formulário
      this.$nextTick(() => {
        const formElement = this.$refs.financeForm;
        if (formElement) {
          formElement.scrollIntoView({ behavior: 'smooth' });
        }
      });
    },

    toggleSortOrder() {
      this.sortOrder = this.sortOrder === 'desc' ? 'asc' : 'desc';
      this.fetchFinances();
    },
  },
  mounted() {
    this.fetchFinances();
  },
};

</script>

<template>
  <div class="finance-container">
    <!-- Formulário -->
    <h1 class="title">{{ formMode === 'edit' ? 'Editar Conta' : 'Nova Conta' }}</h1>

    <form @submit.prevent="submitForm" class="finance-form" ref="financeForm">
      <div class="form-group">
        <label for="description">Descrição:</label>
        <input v-model="formData.description" id="description" type="text" required />
      </div>

      <div class="form-group">
        <label for="value">Valor:</label>
        <input v-model.number="formData.value" id="value" type="text" @input="formatValue" required />
      </div>

      <div class="form-group">
        <label for="due_date">Data de Vencimento:</label>
        <input v-model="formData.due_date" id="due_date" type="date" required />
      </div>

      <div class="form-group">
        <label for="status">Status:</label>
        <select v-model="formData.status" id="status" required>
          <option value="Pendente">Pendente</option>
          <option value="Pago">Pago</option>
          <option value="Atrasado">Atrasado</option>
        </select>
      </div>

      <div class="form-group">
        <label for="type">Tipo:</label>
        <select v-model="formData.type" id="type" required>
          <option value="Pagar">Pagar</option>
          <option value="Receber">Receber</option>
        </select>
      </div>

      <button type="submit" class="submit-btn">
        {{ formMode === 'edit' ? 'Atualizar' : 'Criar' }} Conta
      </button>
    </form>

    <h2 class="subtitle">Exibir contas:</h2>
    <!-- Filtros e Ordenação -->
    <div class="filters">
      <select v-model="filterStatus" @change="fetchFinances">
        <option value="Todos">Todos</option>
        <option value="Pendente">Pendente</option>
        <option value="Pago">Pago</option>
        <option value="Atrasado">Atrasado</option>
      </select>

      <button @click="toggleSortOrder">
        Ordenar por Data: {{ sortOrder === 'desc' ? 'Descrescente' : 'Crescente' }}
      </button>
    </div>

    <!-- Lista de contas -->
    <div v-if="loading" class="loading">Carregando...</div>

    <div v-if="finances.data.length > 0" class="finance-list">

      <ul>
        <li v-for="finance in finances.data" :key="finance.id" class="finance-item">
          <div class="finance-details">
            <span><i class="fas fa-wallet"></i> <strong>A {{ finance.type }}: </strong></span>
            <span><i class="fas fa-file-alt"></i> <strong>{{ finance.description }}</strong></span>
            <span><i class="fas fa-money-bill-wave"></i> <strong>R$: </strong>{{ formatCurrency(finance.value)}}</span>
            <span><i class="fas fa-check-circle"></i> <strong>Status: </strong>{{ finance.status }}</span>
            <span><i class="fas fa-calendar-day"></i> <strong>Data de Vencimento: </strong>{{
              formatDate(finance.due_date) }}</span>
            <span><i class="fas fa-user"></i> <strong>Titular:</strong> {{ finance.user.name }}</span>
          </div>
          <div class="actions">
            <button @click="setEditMode(finance)" class="edit-btn">Editar</button>
            <button @click="deleteFinance(finance.id)" class="delete-btn">Excluir</button>
          </div>
        </li>
      </ul>
    </div>

    <div v-else>
      <p>Nenhuma conta encontrada.</p>
    </div>
  </div>
</template>

<style scoped>
.finance-container {
  margin-top: 20px;
  padding: 20px;
  background-color: #fff;
  border-radius: 10px;
  height: fit-content;
  width: 95%;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  box-sizing: border-box;
  text-align: left;
  margin-bottom: 20px;
}

.title {
  text-align: left;
  font-size: 1.8rem;
  color: #333;
  margin-bottom: 20px;
}

.subtitle {
  text-align: left;
  font-size: 1.8rem;
  color: #333;
  margin-bottom: 20px;
}

.filters {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.filters select,
.filters button {
  padding: 10px;
  font-size: 1rem;
  border: 1px solid #ddd;
  border-radius: 4px;
  background-color: #fff;
  cursor: pointer;
}

.filters select {
  width: 200px;
}

.filters button {
  background-color: #0084ff;
  color: white;
  border: none;
}

.finance-form {
  background-color: #fff;
  padding: 20px;
  border-radius: 8px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  margin-bottom: 28px;
}

.finance-form .form-group {
  margin-bottom: 15px;
}

.finance-form label {
  display: block;
  font-size: 1rem;
  color: #333;
}

.finance-form input,
.finance-form select {
  width: 100%;
  padding: 10px;
  font-size: 1rem;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.finance-form .submit-btn {
  padding: 12px 20px;
  background-color: #4CAF50;
  color: white;
  border: none;
  cursor: pointer;
  font-size: 1.1rem;
  border-radius: 4px;
}

.finance-list {
  margin-top: 30px;
}

.finance-item {
  margin-bottom: 15px;
  background-color: #fff;
  padding: 15px;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.finance-item span {
  display: block;
  font-size: 1rem;
  color: #333;
  margin-bottom: 5px;
}

.finance-details span {
  padding: 4px;
}

.finance-details span:first-child {
  font-size: 1.2rem;
  margin-bottom: 10px;
}

.actions {
  display: flex;
  justify-content: flex-end;
}

.edit-btn,
.delete-btn {
  padding: 8px 16px;
  margin-left: 10px;
  font-size: 1rem;
  cursor: pointer;
  border-radius: 4px;
}

.edit-btn {
  background-color: #ffa500;
  color: white;
  border: none;
}

.delete-btn {
  background-color: #e53935;
  color: white;
  border: none;
}

.loading {
  text-align: center;
  font-size: 1.5rem;
  color: #666;
}
</style>
