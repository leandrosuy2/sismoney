# 🚀 Sismoney - Sistema de Gestão Financeira

<div align="center">
  <img src="public/images/logo.png" alt="Sismoney Logo" width="200"/>
  
  [![Laravel](https://img.shields.io/badge/Laravel-12.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
  [![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
  [![TailwindCSS](https://img.shields.io/badge/TailwindCSS-4.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
  [![Vite](https://img.shields.io/badge/Vite-6.x-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev)
</div>

## 📋 Sobre o Projeto

Sismoney é um sistema moderno de gestão financeira desenvolvido com Laravel 12 e tecnologias web atuais. O sistema oferece uma solução completa para gerenciamento de finanças pessoais e empresariais, incluindo controle de empréstimos, contas a pagar e receber.

## ✨ Funcionalidades Principais

- 🔐 **Autenticação Segura**
  - Login/Registro de usuários
  - Sistema de autenticação dupla para empréstimos
  - Proteção de rotas

- 💰 **Gestão de Empréstimos**
  - Cadastro e controle de empréstimos
  - Acompanhamento de pagamentos
  - Área específica para usuários de empréstimos

- 📊 **Controle Financeiro**
  - Contas a Pagar
  - Contas a Receber
  - Dashboard com visão geral das finanças

## 🛠️ Tecnologias Utilizadas

- **Backend**
  - PHP 8.2+
  - Laravel 12
  - Laravel Sanctum
  - Laravel Tinker

- **Frontend**
  - Vite
  - TailwindCSS 4
  - Axios

- **Ferramentas de Desenvolvimento**
  - Laravel Sail
  - Laravel Pint
  - PHPUnit
  - Laravel Pail

## 🚀 Como Executar o Projeto

1. **Clone o repositório**
   ```bash
   git clone https://github.com/leandrosuy2/sismoney.git
   cd sismoney
   ```

2. **Instale as dependências do PHP**
   ```bash
   composer install
   ```

3. **Configure o ambiente**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Configure o banco de dados**
   - Crie um banco de dados MySQL
   - Configure as credenciais no arquivo `.env`
   - Execute as migrações:
     ```bash
     php artisan migrate
     ```

5. **Instale as dependências do Node.js**
   ```bash
   npm install
   ```

6. **Inicie o servidor de desenvolvimento**
   ```bash
   composer dev
   ```

## 📦 Estrutura do Projeto

```
sismoney/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   └── Middleware/
│   ├── Models/
│   └── Providers/
├── config/
├── database/
├── public/
├── resources/
├── routes/
└── tests/
```

## 🤝 Contribuindo

1. Faça um Fork do projeto
2. Crie uma Branch para sua Feature (`git checkout -b feature/AmazingFeature`)
3. Faça o Commit das suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Faça o Push para a Branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.

## 👨‍💻 Autor

Leandro Suy - [GitHub](https://github.com/leandrosuy2)

## 📞 Suporte

Para suporte, envie um email para [seu-email@exemplo.com] ou abra uma issue no GitHub.

---

<div align="center">
  <sub>Desenvolvido com ❤️ por Leandro Suy</sub>
</div>
