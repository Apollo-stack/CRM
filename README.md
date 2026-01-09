# CRM - Sistema de Gestão de Relacionamento com Cliente

Sistema completo de CRM desenvolvido com Laravel 12, com dashboard analítico, pipeline de vendas visual e busca avançada.

## 🚀 Funcionalidades

- Dashboard com métricas e gráficos
- Gestão completa de clientes
- Pipeline de vendas estilo Kanban
- Histórico de interações (ligações, emails, reuniões)
- Busca global inteligente
- Filtros e ordenação avançados

## 🛠️ Tecnologias

- Laravel 12.45
- MySQL
- TailwindCSS
- Chart.js

## 📸 Screenshots

[Adicionar prints do dashboard, pipeline, etc]

## ⚙️ Instalação
```bash
git clone https://github.com/Apollo-stack/CRM
cd CRM
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```
```

---

### **2. Exportar Relatórios (MUITO VALORIZADO)**
Adiciona botão para exportar dados em Excel/PDF. Isso impressiona MUITO.

**Onde adicionar:**
- No dashboard → Exportar relatório mensal
- Na lista de clientes → Exportar lista em Excel
- No pipeline → Exportar negócios por status

**Biblioteca sugerida:** `maatwebsite/excel` para Excel, `barryvdh/laravel-dompdf` para PDF

---

### **3. Drag & Drop no Pipeline (WOW Factor)**
Ao invés de clicar nos botões, **arrastar** os cards entre as colunas. Isso deixa o CRM muito mais profissional.

**Biblioteca sugerida:** `SortableJS` ou `dragula.js`

---

### **4. Notificações/Lembretes**
Sistema simples de lembretes:
- "Ligar pro cliente X amanhã"
- "Follow-up pendente há 3 dias"
- Badge com contador no menu

---

## 💼 MELHORIAS TÉCNICAS PARA PORTFÓLIO (Prioridade MÉDIA)

### **5. API REST**
Cria endpoints da API pra mostrar que você sabe trabalhar com:
```
GET /api/clients
GET /api/leads
POST /api/leads
