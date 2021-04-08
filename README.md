# desafio

Endpoints:

url/api/comment/write.php?usuario={1}&post={2}&conteudo='{3}'
  .1 - id do usuario que comenta
  .2 - id do post
  .3 - conteudo do comentário

url/api/comment/read.php?page={1}
  .1 - Numero da página atual. Se não setada fica com valor 1
  
url/api/notification/readOne.php?user={1}
  .1 - Id do usuário que recebe a notificação
