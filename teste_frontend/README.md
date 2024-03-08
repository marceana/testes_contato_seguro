# Desafio | Frontend

Este é um **CRUD** apenas no **lado do cliente**, ou seja, irá manter as informações armazenadas no **localStorage** do navegador.

As regras dos campos sendo utilizados são as seguintes:

- Nome: Marcelli Marinho Assumpção
- E-mail: assumpcaom23@gmail.com
- Telefone: (51) 99498-4052
- Data de nascimento: 23/04/1995
- Cidade onde nasceu: Araruama

3 bugs foram inseridos no código. Um no HTML, um no CSS (vide o arquivo layout.jpg para referência) e um no JS (localizado na classe MyCrud).
Além disso, você deve implementar o método de remoção (localizado na classe MyCrud).

Serão avaliadas suas capacidades de leitura de código e resolução de problemas. Não se preocupe caso não consiga terminar tudo, nos envie o teste mesmo assim, destacando, logo abaixo, suas principais dificuldades e como fez para resolver os problemas.

# Resposta do participante

_Responda aqui quais foram suas dificuldades e como fez para encontrar e resolver os problemas ao enviar a solução_
Primeiramente eu ajustei o css da classe logo para alinhar a mesma ao centro mudando de "left" para "center", além de retirar o atributo class da imagem já que não seria necessário.
Já no arquivo "crud.js", na classe MyCrud e na função update havia um erro que quando eu tentava editar um registro da tabela, ao invés colocar os valores que eu digitei na modal, os valores eram atualizados para as chaves do objeto inserido no array, então ficava "nome", "email", "telefone", "nascimento" e "cidade" no lugar dos reais valores digitados. Para ajustar isso só foi preciso atribuir os valores do objeto "map" a tabela, usando a chave "key" para acessar a propriedade certa.
Na função delete só precisei usar o método splice que retira um registro do array passando um índice e a quantidade de valores a serem retirados a partir daquele índice e depois atualizar os valores da tabela no local storage.
