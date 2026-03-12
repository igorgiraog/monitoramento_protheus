# Monitoramento de Serviços Protheus

## 📖 Sobre o Projeto

Este projeto foi desenvolvido para monitorar os principais serviços do ERP Protheus (TOTVS), oferecendo uma visão centralizada e em tempo real do status de cada componente essencial para a operação do sistema.

A solução nasceu da necessidade de obter uma ferramenta simples e eficaz para a equipe de TI, permitindo a identificação rápida de instabilidades e agilizando a tomada de decisão.

<img width="2559" height="1079" alt="image" src="https://github.com/user-attachments/assets/a3ec1999-25e6-441a-a2e5-ecd0ba7279ea" />

## ✨ Funcionalidades

*   Monitoramento do serviço de `License Server`.
*   Monitoramento do serviço do `AppServer`.
*   Monitoramento do serviço do `DBAccess`.
*   Painel de configuração web para adicionar e remover serviços.

## 💻 Tecnologias Utilizadas

*   **Backend:** PHP
*   **Frontend:** HTML, CSS, JavaScript
*   **Banco de Dados:** Json
*   **Frameworks/Bibliotecas:** Tailwindcss

## ⚠️ Pré-requisitos: Configuração do Protheus

Para que o monitoramento do AppServer funcione, é necessário habilitar o monitor de aplicação no arquivo de configuração (`appserver.ini`) do seu servidor Protheus. Adicione a seguinte seção, conforme a documentação oficial da TOTVS:

```ini
[APP_MONITOR]
enable=1
port=20022
```

**Utilizando SSL (Opcional):**

Caso você utilize um certificado SSL para a comunicação, adicione as seguintes chaves à seção `[APP_MONITOR]`:

```ini
[APP_MONITOR]
enable=1
port=40044
SslMethod=SSL/TLS
SslCertificate="C:\\caminho\\para\\seu\\certificado.crt"
SslCertificateKey="C:\\caminho\\para\\sua\\chave.pem"
;SslCertificatePass=sua_senha_se_houver
```

Se você **não** for utilizar SSL, basta manter as chaves `enable` e `port`.

## 🚀 Como Instalar e Executar

1.  **Clone o repositório:**
    ```bash
    git clone https://github.com/igorgiraog/monitoramento_protheus.git
    ```
    Coloque os arquivos em um servidor web com suporte a PHP.

2.  **Configure os Servidores Protheus:**
    *   Certifique-se de que a configuração `[APP_MONITOR]` (descrita na seção de Pré-requisitos) está ativa nos AppServers que você deseja monitorar.

3.  **Adicione os Serviços:**
    *   Abra seu navegador e acesse a página `config.php` do projeto (ex: `http://localhost/seu-projeto/config.php`).
    *   Utilize a interface para adicionar os IPs e portas dos serviços Protheus (AppServer, License Server, DBAccess, etc.) que você deseja acompanhar.

4.  **Visualize o Painel:**
    *   Após adicionar os serviços, acesse a página inicial `index.php` (ex: `http://localhost/seu-projeto/index.php`).
    *   O painel já estará exibindo o status dos serviços cadastrados.

## 🤝 Como Contribuir

Este é um projeto open source e contribuições são muito bem-vindas! Se você tem ideias para melhorias, novas funcionalidades ou correções, sinta-se à vontade para:

1.  Fazer um "Fork" do projeto.
2.  Criar uma nova "Branch" (`git checkout -b feature/sua-feature`).
3.  Fazer o "Commit" das suas alterações (`git commit -m 'Adiciona nova feature'`).
4.  Fazer o "Push" para a "Branch" (`git push origin feature/sua-feature`).
5.  Abrir um "Pull Request".

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo [LICENSE](LICENSE) para mais detalhes.
