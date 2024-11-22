# Sistema de Gestão de Acessos e Configurações

Este é um sistema de gestão de usuários, controle de acesso, configurações dinâmicas e integração com APIs externas, desenvolvido como um monólito utilizando **Laravel**, **Inertia.js** e **Vue.js**. O sistema implementa funcionalidades como autenticação, controle de acesso com base em papéis de usuário, configuração global exclusiva para usuários Master e integração com APIs externas como Busca CEP dos Correios.

## Funcionalidades Principais

### A. Autenticação e Controle de Acesso (ACL)
- **Níveis de Usuário:**
  - **Master:** Controle total sobre o sistema, incluindo configurações globais.
  - **Administrador:** Acesso total aos módulos, exceto configurações globais.
  - **Usuário Comum:** Acesso apenas aos módulos de visualização e edição próprios.
  
- **Restrições de Página e Componentes:**
  - Middleware Laravel utilizado para controlar o acesso às rotas com base no tipo de usuário.
  - No frontend (Vue.js), componentes são ocultados de acordo com as permissões do usuário logado, como componentes de edição que só aparecem para administradores e usuários Master.

### B. Configuração Global Exclusiva para Usuário Master
- **Configuração Global:**
  - O usuário Master pode definir a cor de fundo da aplicação, que será aplicada globalmente a todos os usuários.
  - A configuração é armazenada no banco de dados e aplicada dinamicamente no frontend usando Vue.js.

### C. Integração com API Externa
  - **Correios:** Permite consultar o CEP inserido pelo usuário e obter informações de endereço.

- **Autenticação e Restrição de Acesso:**
  - Funcionalidades de integração com API restritas a usuários Master e Administrador, com autenticação segura via JWT e Sanctum.

### D. Funcionalidade de Contas a Pagar e Receber
- **CRUD de Contas:**
  - Campos: Descrição, Valor, Data de Vencimento, Status (Pendente, Pago, Atrasado) e Tipo (Pagar ou Receber).
  
- **Restrições de Visualização e Edição:**
  - Usuários comuns podem ver e editar apenas as próprias contas.
  - Administradores e usuários Master têm acesso completo a todas as contas, podendo filtrar, adicionar, editar e excluir registros.

## Requisitos Técnicos

### A. Backend - Laravel
- **Controle de Acesso:**
  - O controle de acesso é implementado com políticas e middleware no Laravel, garantindo que cada usuário tenha acesso apenas ao que é permitido para seu nível.
  
- **API para Configuração Global:**
  - Uma API foi criada para gerenciar a configuração global da cor de fundo, utilizando cache para melhorar a performance.
  
- **Integração com API Externa:**
  - A integração com a API externa (Busca CEP dos Correios) é realizada de forma segura, com autenticação adequada e chaves de API armazenadas no arquivo ```.env```.

### B. Frontend - Vue.js
- **Autenticação com JWT:**
  - A autenticação é gerida com tokens JWT, que são enviados em cada requisição para garantir a segurança e identificar o usuário logado.

- **Configuração Global Dinâmica:**
  - O Vuex é utilizado para armazenar a configuração global (cor de fundo) e aplicá-la dinamicamente em toda a aplicação.

- **Interface Intuitiva:**
  - O sistema conta com um dashboard simples e responsivo, onde a barra de navegação é adaptada ao nível de acesso do usuário, exibindo os links e componentes correspondentes.

### C. Arquitetura do Sistema
- O projeto foi desenvolvido como um **monólito** utilizando as tecnologias **Laravel**, **Inertia.js** e **Vue.js**:
  - **Laravel** serve como o backend e a camada de controle de acesso (ACL).
  - **Inertia.js** facilita a integração do backend com o frontend, permitindo que o Vue.js seja usado no frontend sem a necessidade de um API REST separada.
  - **Vue.js** é responsável pela renderização do frontend e pela interatividade da aplicação, utilizando Vuex para gerenciar o estado da aplicação e manter a reatividade.

## Tecnologias Utilizadas

- **Backend:** Laravel, com autenticação JWT e middleware de controle de acesso.
- **Frontend:** Vue.js, Inertia.js, Vuex para gerenciamento de estado, integração com APIs externas e renderização dinâmica de componentes.
- **Banco de Dados:** MySQL (ou outro banco de dados relacional).
- **APIs:** Integração com API busca de endereços pelo CEP para funcionalidades externas.
- **Segurança:** Autenticação via JWT, configuração segura de chaves de API no arquivo .env.


## Instruções para Execução

1. **Clone o repositório:**
   
   ```bash
   git clone https://github.com/blimabru/gestao_contas.git
   cd gestao_contas
   ```

2. **Mude para a Branch de Desenvolvimento:**

    ```bash
    git checkout Development
    ```

3. **Instale as dependências:**
   
   ```bash
   composer install
   npm install
   npm run build
   ```

4. **Configure o ambiente:** Crie uma cópia do arquivo ```.env.example```, renomeie-a para ```.env``` e configure as variáveis de ambiente, incluindo as chaves de API.

5. **Execute as migrações do banco de dados:**
   
   1. **Cria os acessos de usuário iniciais:** opcional

      ```bash
      php artisan migrate --seed
      ```

      **Esse comando cria 3 usuários de teste** com os seguintes logins e senhas:

      - **Login:** master@master.com (senha: master@master.com)
      - **Login:** admin@admin.com (senha: admin@admin.com)
      - **Login:** user@user.com (senha: user@user.com)
  
     2. **Popula o banco de dados com 10 contas financeiras fictícias utilizando:** opcional
   
      ```bash
        php artisan db:seed --class=FinanceSeeder
      ```
      
5. **Crie as chaves de criptografia da aplicação:**
   ```bash
   php artisan key:generate
   php artisan jwt:secret
   ```

6. **Inicie o servidor de desenvolvimento:**
   ```bash
   composer run dev
   ```

7. **Acesse a aplicação no navegador:** A aplicação estará disponível em http://localhost:8000

## Contribuição

### Se você deseja contribuir com melhorias para o projeto, siga estas etapas:

1. **Faça um fork deste repositório.**
   
2. **Crie uma nova branch:**
   
   ```bash
   git checkout -b feature/nova-feature
   ```

3. **Realize as alterações e faça commit delas:**

   ```bash
   git commit -m 'Adiciona nova feature'
   ```

4. **Faça push para a branch:**
   
   ```bash
   git push origin feature/nova-feature
   ```

5. E por último abra um pull request descrevendo suas alterações ❤️
