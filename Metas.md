# 📚 CHECKLIST DE MELHORIAS — SOCIEDADE LITERÁRIA DO CANDELABRO

**Projeto:** Clube do Livro Online  
**Objetivo:** Melhorar a segurança, interface, experiência do usuário, desempenho e funcionalidades da plataforma.

---

## 🔴 1. Segurança Web — Prioridade Crítica

### Proteção do servidor e arquivos
- [ ] Mover o banco SQLite para fora do diretório público.
- [ ] Proteger arquivos de configuração e informações sensíveis.
- [ ] Criar uma pasta `public/` para os arquivos acessíveis pela internet.
- [ ] Bloquear acesso direto a bancos de dados, backups e logs.
- [ ] Configurar corretamente o `.htaccess` ou as regras do servidor.
- [ ] Criar um arquivo `.env` para configurações sensíveis.
- [ ] Adicionar arquivos sensíveis ao `.gitignore`.
- [ ] Configurar HTTPS em produção.
- [ ] Implementar cabeçalhos de segurança HTTP.
- [ ] Configurar Content-Security-Policy (CSP).
- [ ] Configurar HSTS quando o site utilizar HTTPS corretamente.

### Segurança da API
- [ ] Implementar rate limiting nas requisições.
- [ ] Proteger endpoints contra requisições automatizadas abusivas.
- [ ] Implementar proteção CSRF nas operações autenticadas por sessão.
- [ ] Fortalecer a validação dos dados recebidos no back-end.
- [ ] Validar os tipos dos dados enviados pelo usuário.
- [ ] Padronizar códigos HTTP e respostas de erro.
- [ ] Impedir exposição de mensagens internas do PHP e do banco.
- [ ] Criar logs de erros e eventos de segurança.
- [ ] Configurar limites para criação de publicações.
- [ ] Implementar controle de permissões nos endpoints.

### Segurança dos uploads
- [ ] Validar extensões e tipos MIME dos arquivos.
- [ ] Definir limites de tamanho e dimensões das imagens.
- [ ] Gerar nomes aleatórios para arquivos enviados.
- [ ] Reprocessar imagens antes de armazená-las.
- [ ] Converter imagens para WebP ou AVIF quando apropriado.
- [ ] Remover metadados desnecessários das imagens.
- [ ] Impedir execução de arquivos enviados pelos usuários.
- [ ] Excluir imagens órfãs quando ocorrer falha no cadastro.
- [ ] Implementar denúncia e moderação de imagens.

### Proteção de dados
- [ ] Criar política de privacidade.
- [ ] Implementar medidas de adequação à LGPD.
- [ ] Permitir exclusão dos dados pessoais dos usuários.
- [ ] Criar rotina de backup do banco de dados.
- [ ] Testar a restauração dos backups.

---

## 🟠 2. Autenticação e Gerenciamento de Usuários

- [ ] Criar sistema de cadastro de usuários.
- [ ] Criar página de login.
- [ ] Implementar logout.
- [ ] Armazenar senhas utilizando hash seguro.
- [ ] Implementar sessões seguras.
- [ ] Configurar cookies HttpOnly, Secure e SameSite.
- [ ] Criar recuperação de senha por e-mail.
- [ ] Implementar verificação de e-mail.
- [ ] Criar níveis de permissão: administrador, moderador e membro.
- [ ] Vincular publicações ao usuário autenticado.
- [ ] Permitir edição das próprias publicações.
- [ ] Permitir exclusão das próprias publicações.
- [ ] Impedir edição ou exclusão de publicações de terceiros.
- [ ] Implementar proteção contra tentativas excessivas de login.
- [ ] Permitir alteração de senha.
- [ ] Permitir exclusão de conta.

---

## 🟡 3. Layout e Interface (UI)

### Landing page
- [ ] Melhorar a apresentação da seção inicial (hero).
- [ ] Criar uma frase de destaque explicando a proposta do clube.
- [ ] Adicionar botão principal para participar do clube.
- [ ] Adicionar botão para explorar a estante.
- [ ] Exibir estatísticas reais da comunidade.
- [ ] Melhorar a hierarquia visual dos textos.
- [ ] Refinar espaçamentos entre seções.
- [ ] Melhorar a apresentação das imagens e ilustrações.
- [ ] Preservar e aprimorar a identidade visual vitoriana.
- [ ] Melhorar o rodapé com informações institucionais.

### Navegação
- [ ] Implementar cabeçalho fixo ou sticky.
- [ ] Melhorar o menu de navegação mobile.
- [ ] Indicar visualmente a seção ativa.
- [ ] Adicionar navegação por teclado.
- [ ] Melhorar a transição entre seções.
- [ ] Adicionar botão para voltar ao topo.
- [ ] Criar navegação específica para usuários autenticados.

### Cards dos livros
- [ ] Melhorar o design dos cards.
- [ ] Destacar título e autor.
- [ ] Adicionar status de leitura.
- [ ] Adicionar avaliações por estrelas.
- [ ] Adicionar botão para comentários.
- [ ] Exibir data da publicação.
- [ ] Melhorar visualização de tags e gêneros.
- [ ] Criar página detalhada para cada livro.
- [ ] Adicionar botão para salvar livro na biblioteca pessoal.

### Identidade visual
- [ ] Padronizar cores e componentes.
- [ ] Padronizar tipografia e tamanhos de fonte.
- [ ] Criar componentes visuais reutilizáveis.
- [ ] Melhorar os temas claro e escuro.
- [ ] Refinar o tema de alto contraste.
- [ ] Padronizar botões, campos e mensagens.
- [ ] Criar animações discretas e consistentes.

---

## 🟢 4. Experiência do Usuário (UX)

### Formulários
- [ ] Melhorar o formulário de cadastro de leituras.
- [ ] Exibir erros diretamente abaixo dos campos.
- [ ] Validar campos obrigatórios antes do envio.
- [ ] Destacar o primeiro campo inválido.
- [ ] Melhorar a seleção e pré-visualização de imagens.
- [ ] Permitir remover uma imagem selecionada.
- [ ] Exibir carregamento durante o envio.
- [ ] Impedir envios duplicados.
- [ ] Exibir mensagem de sucesso após publicação.
- [ ] Preservar dados preenchidos quando ocorrer erro.

### Experiência de navegação
- [ ] Criar skeleton loading para carregamento de livros.
- [ ] Melhorar mensagens quando não houver resultados.
- [ ] Criar feedback visual para curtidas.
- [ ] Criar confirmação antes de excluir uma publicação.
- [ ] Melhorar a experiência em telas pequenas.
- [ ] Corrigir a navegação pelo teclado no lightbox.
- [ ] Implementar gerenciamento correto de foco nos modais.
- [ ] Adicionar ferramenta de pesquisa de livros.
- [ ] Criar filtros combinados por gênero, autor e status.
- [ ] Permitir ordenar livros por data, título, avaliação e popularidade.

---

## 🔵 5. Acessibilidade

- [ ] Seguir as recomendações WCAG 2.2 nível AA.
- [ ] Verificar contraste de cores.
- [ ] Garantir navegação completa pelo teclado.
- [ ] Adicionar descrições alternativas às imagens.
- [ ] Melhorar nomes acessíveis de botões e ícones.
- [ ] Implementar foco visível em elementos interativos.
- [ ] Melhorar a acessibilidade dos modais.
- [ ] Adicionar descrições textuais aos gráficos.
- [ ] Corrigir o uso de atributos ARIA.
- [ ] Evitar anúncios excessivos com `aria-live`.
- [ ] Respeitar a preferência por movimento reduzido.
- [ ] Garantir tamanhos confortáveis para áreas de toque.
- [ ] Testar o site com leitores de tela.

---

## 🟣 6. Performance e Otimização

### Front-end
- [ ] Implementar paginação na estante.
- [ ] Carregar livros progressivamente.
- [ ] Evitar renderização excessiva de cards.
- [ ] Cancelar requisições antigas utilizando AbortController.
- [ ] Otimizar JavaScript e CSS para produção.
- [ ] Remover recursos e estilos não utilizados.
- [ ] Otimizar carregamento das fontes.
- [ ] Implementar cache para arquivos estáticos.
- [ ] Configurar compressão Gzip ou Brotli.
- [ ] Utilizar versionamento de arquivos CSS e JS.

### Imagens
- [ ] Redimensionar imagens no servidor.
- [ ] Gerar miniaturas para a estante.
- [ ] Utilizar imagens maiores apenas na visualização detalhada.
- [ ] Implementar `srcset` e `sizes` quando necessário.
- [ ] Manter lazy loading das imagens fora da tela.
- [ ] Evitar mudanças inesperadas no layout ao carregar imagens.

### Banco e API
- [ ] Implementar LIMIT e OFFSET ou paginação por cursor.
- [ ] Criar índices para consultas frequentes.
- [ ] Otimizar consultas SQL.
- [ ] Evitar consultas desnecessárias.
- [ ] Avaliar configuração WAL do SQLite.
- [ ] Monitorar concorrência e crescimento do banco.
- [ ] Avaliar migração futura para PostgreSQL.

### Monitoramento
- [ ] Realizar testes com Google Lighthouse.
- [ ] Medir LCP, INP e CLS.
- [ ] Medir tempo de resposta da API.
- [ ] Realizar testes de carga.
- [ ] Identificar requisições lentas.
- [ ] Monitorar erros de produção.

---

## 📖 7. Funcionalidades Literárias

### Perfil do leitor
- [ ] Criar página de perfil.
- [ ] Permitir adicionar foto ou avatar.
- [ ] Permitir editar biografia.
- [ ] Exibir gêneros literários favoritos.
- [ ] Exibir quantidade de livros concluídos.
- [ ] Exibir histórico de leitura.
- [ ] Exibir avaliações realizadas.
- [ ] Permitir configurar privacidade do perfil.

### Biblioteca pessoal
- [ ] Criar a página Minha Biblioteca.
- [ ] Implementar lista Quero Ler.
- [ ] Implementar lista Lendo.
- [ ] Implementar lista Concluídos.
- [ ] Implementar lista Abandonados.
- [ ] Permitir atualizar o progresso de leitura.
- [ ] Permitir editar avaliações.
- [ ] Registrar datas de início e conclusão.
- [ ] Adicionar metas anuais de leitura.
- [ ] Criar estatísticas pessoais.

### Diário de leitura
- [ ] Criar diário literário pessoal.
- [ ] Permitir registrar anotações de leitura.
- [ ] Permitir registrar citações curtas.
- [ ] Permitir acompanhar páginas lidas.
- [ ] Exibir histórico de atualizações.
- [ ] Permitir anotações privadas ou públicas.

---

## 💬 8. Comunidade e Interação Social

- [ ] Criar sistema de comentários.
- [ ] Permitir responder comentários.
- [ ] Implementar curtidas vinculadas ao usuário.
- [ ] Permitir remover uma curtida.
- [ ] Impedir curtidas repetidas na mesma publicação.
- [ ] Implementar avaliações de 1 a 5 estrelas.
- [ ] Criar marcação de spoilers.
- [ ] Permitir denunciar publicações.
- [ ] Permitir denunciar comentários.
- [ ] Criar moderação de conteúdo.
- [ ] Implementar notificações de interações.
- [ ] Permitir compartilhar páginas de livros.
- [ ] Criar feed de atividades da comunidade.

---

## 🕯️ 9. Clube do Livro e Leitura Coletiva

- [ ] Criar grupos de leitura.
- [ ] Permitir entrada e saída de grupos.
- [ ] Definir administradores e moderadores dos grupos.
- [ ] Implementar votação para o livro do mês.
- [ ] Criar calendário de leituras coletivas.
- [ ] Organizar cronogramas por capítulos.
- [ ] Criar discussões específicas por livro.
- [ ] Permitir comentários com proteção contra spoilers.
- [ ] Criar eventos e encontros literários.
- [ ] Permitir inscrição em encontros.
- [ ] Criar lembretes de encontros e leituras.
- [ ] Exibir próximos eventos na landing page.

---

## 🏆 10. Gamificação

- [ ] Criar sistema de conquistas literárias.
- [ ] Criar conquista Primeira Chama.
- [ ] Criar conquista Guardião da Vela.
- [ ] Criar conquista Mestre da Estante.
- [ ] Criar conquista Explorador Literário.
- [ ] Criar desafios mensais de leitura.
- [ ] Implementar metas anuais.
- [ ] Criar retrospectiva literária do ano.
- [ ] Exibir medalhas no perfil.
- [ ] Criar desafios coletivos entre membros.

---

## 🗄️ 11. Banco de Dados e Arquitetura

- [ ] Criar tabela de usuários (`users`).
- [ ] Criar tabela de livros (`books`).
- [ ] Separar catálogo de livros de registros de leitura.
- [ ] Adaptar a tabela `readings` para armazenar leituras individuais.
- [ ] Criar tabela de curtidas (`reading_likes`).
- [ ] Criar tabela de comentários (`comments`).
- [ ] Criar tabela de clubes (`clubs`).
- [ ] Criar tabela de membros dos clubes (`club_members`).
- [ ] Criar tabela de encontros (`club_meetings`).
- [ ] Criar tabela de notificações (`notifications`).
- [ ] Criar tabela de denúncias (`reports`).
- [ ] Criar histórico de atualizações de progresso.
- [ ] Implementar chaves estrangeiras e restrições de integridade.
- [ ] Criar migrations para evolução do banco.
- [ ] Padronizar nomes das tabelas e colunas.
- [ ] Revisar relacionamentos e índices.
- [ ] Organizar a API por recursos.
- [ ] Padronizar validações e respostas do back-end.

---

## ⚙️ 12. Painel Administrativo

- [ ] Criar dashboard administrativo.
- [ ] Exibir estatísticas gerais da comunidade.
- [ ] Permitir gerenciamento de usuários.
- [ ] Gerenciar permissões e papéis.
- [ ] Moderar publicações.
- [ ] Moderar comentários.
- [ ] Gerenciar denúncias.
- [ ] Bloquear usuários que violem as regras.
- [ ] Gerenciar grupos de leitura.
- [ ] Cadastrar encontros.
- [ ] Organizar votações e livros do mês.
- [ ] Criar comunicados para membros.
- [ ] Exibir relatórios de participação.

---

## 🌐 13. SEO e Divulgação

- [ ] Melhorar título e descrição das páginas.
- [ ] Criar `robots.txt`.
- [ ] Criar `sitemap.xml`.
- [ ] Configurar metadados Open Graph.
- [ ] Criar imagens para compartilhamento.
- [ ] Implementar URLs amigáveis.
- [ ] Criar páginas públicas de livros.
- [ ] Criar páginas públicas de clubes.
- [ ] Adicionar dados estruturados Schema.org quando apropriado.
- [ ] Melhorar indexação das páginas públicas.
- [ ] Criar seção de perguntas frequentes.
- [ ] Criar página explicando como participar do clube.
- [ ] Adicionar links para redes sociais.

---

## 🧪 14. Testes e Qualidade de Código

- [ ] Configurar PHPUnit.
- [ ] Criar testes unitários para validações.
- [ ] Criar testes de integração para o banco.
- [ ] Criar testes da API.
- [ ] Testar criação, edição e exclusão de leituras.
- [ ] Testar autenticação e permissões.
- [ ] Testar tratamento de entradas inválidas.
- [ ] Testar upload de arquivos.
- [ ] Testar proteção contra acesso não autorizado.
- [ ] Criar testes automatizados com Playwright.
- [ ] Testar interface em dispositivos móveis.
- [ ] Testar compatibilidade entre navegadores.
- [ ] Configurar PHPStan para análise estática.
- [ ] Realizar verificações com OWASP ZAP.
- [ ] Configurar GitHub Actions.
- [ ] Executar testes automaticamente antes de publicar.

---

## 📁 15. Organização e Documentação

- [ ] Revisar estrutura de pastas.
- [ ] Separar arquivos públicos e privados.
- [ ] Padronizar nomes de arquivos e classes.
- [ ] Manter responsabilidades separadas entre Controller, Service e Repository.
- [ ] Criar documentação da API.
- [ ] Documentar estrutura do banco de dados.
- [ ] Criar instruções de instalação local.
- [ ] Criar `.env.example`.
- [ ] Melhorar o README do GitHub.
- [ ] Criar histórico de alterações (CHANGELOG).
- [ ] Criar checklist de publicação.
- [ ] Documentar procedimentos de backup.
- [ ] Criar testes de verificação rápida (smoke tests).

---

# 🚀 ORDEM RECOMENDADA DE DESENVOLVIMENTO

- [ ] **ETAPA 1:** Corrigir vulnerabilidades de segurança críticas.
- [ ] **ETAPA 2:** Fortalecer a API, uploads e validações.
- [ ] **ETAPA 3:** Melhorar layout, responsividade e acessibilidade.
- [ ] **ETAPA 4:** Implementar paginação e otimizações de performance.
- [ ] **ETAPA 5:** Criar autenticação e gerenciamento de usuários.
- [ ] **ETAPA 6:** Refatorar o banco para separar livros e leituras.
- [ ] **ETAPA 7:** Criar perfis e bibliotecas pessoais.
- [ ] **ETAPA 8:** Implementar comentários, curtidas e avaliações.
- [ ] **ETAPA 9:** Criar painel administrativo e moderação.
- [ ] **ETAPA 10:** Implementar grupos, livro do mês e encontros.
- [ ] **ETAPA 11:** Adicionar gamificação e desafios literários.
- [ ] **ETAPA 12:** Finalizar SEO, testes automatizados e monitoramento.

---

**Observação:** Alguns itens já possuem implementação parcial no projeto, como validação de MIME, nomes aleatórios de uploads, temas de interface e tratamento de erros. Nesses casos, a tarefa consiste em revisar, complementar e testar o recurso existente, não necessariamente refazê-lo.

**Meta final:** transformar a Sociedade Literária do Candelabro em uma plataforma segura, responsiva, acessível e interativa, que reúna leitores e promova experiências de leitura coletiva.
