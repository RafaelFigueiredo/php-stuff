# Php Stuff
This repositorý will archieve some php for parts of projects

## Webservice
*Tecnologias envolvidas: PHP, JSON Parsing, MySQL*

Este webservice segue o padrão descrito no documento *Documento de Interface - Sistema SGZ – WS EnviarOS* da Secretaria Municipal de Sub Prefeituras da Prefeitura de São Paulo.

Esse webservice roda na empresa que esteja cadastrada no sistema para receber via `url` as ordens de serviço. Ele então:
* Recebe arquivo `JSON` no endpoint informado a secretaria.
* Faz as verificações de segurança (não especificado pelo documento mas necessário para evitar ataques dos mais simples)
* Armazena a requisição no banco de dados para posterior consumo por outros aplicativos.
### Entrada de dados
* Arquivo `JSON`
### Saída
* `HTTP 200` - Dados recebidos
* `HTTP 400` - Dados serão enviados novamente


### Instalação
Deve ser instalado em servidor com suporte a PHP e MySQL apenas copiando o arquivo `webservice.php` para a pasta do endpoint, por exemplo: `public_html\api\` para ser acessado no seu dominio como `www.meudominio.com\api`.

Edite o começo do arquivo para configurar o acesso ao banco de dados.
```php
//variáveis do banco de $dadosDaOs
define("servername", "localhost");
define("dbname", "DATABASE NAME");
define("username", "USERNAME");
define("password", "PASSWORD");
```

### Banco de dados
Crie uma tabela no seu banco de dados chamada `requisicoes`
```sql
CREATE TABLE IF NOT EXISTS `requisicoes` (
  `id` bigint(20) NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `reg_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
```


### Uso
```js
function enviarJSON(){
    var arr = { City: 'Moscow', Age: 25 };
    $.ajax({
        url: 'http://cariocaencoder.com/ordensdeservico/webservice.php',
        type: 'POST',
        data: JSON.stringify(arr),
        contentType: 'application/json; charset=utf-8',
        dataType: 'json',
        async: false,
        success: function(msg) {
            alert(msg);
        }
    });

    return false;
  }
  ```
