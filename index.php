<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Teste NVPC</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
</head>
<style>
	.btn-group,
	.btn-group-vertical,
	.ui-datepicker-trigger {
		display: none !important;
	}

	.container {
		background-color: #f8f9fa;
		border: 2px solid rgba(28, 110, 164, 0.15);
		border-radius: 7px 7px 7px 7px;
		margin-top: 10px;
	}

	button {
		width: 100px;
		height: 50px;
	}

	img {
		height: 40px;
	}
</style>

<body>

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="col-sm-12 table-responsive">

					<form method="POST" id="form">
						<div class="form-row">
							<div class="form-group col-md-2">
								<label for="user">Username do GitHub</label>
								<input id="user" class="form-control" name="user" type="text" required value="thallesaguiar">
							</div>
							<div class="form-group col-md-6">
								<label for="search">Procurar por</label>
								<input id="search" class="form-control" name="search" type="text" placeholder="api json github">
							</div>
							<div class="form-group col-md-2" hidden>
								<label for="type">Tipo</label>
								<select id="type" class="form-control" name="type">
									<option selected value="repositories">Repositórios</option>
									<!-- <option value="commits">Commits</option>
									<option value="labels">Labels</option> -->
								</select>
							</div>
							<div class="form-group col-md-2">
								<label for="language">Linguagem</label>
								<input id="language" class="form-control" name="language" type="text">
							</div>
						</div>

						<div class="form-row">
							<div class="form-group col-md-2">
								<label for="order">Ordernar por</label>
								<select id="order" class="form-control" name="order">
									<option selected value="desc">Decrescente</option>
									<option value="asc">Crescente</option>
								</select>
							</div>
							<div class="form-group col-md-2">
								<label for="sort">Classificar por</label>
								<select id="sort" class="form-control" name="sort">
									<option selected value="<?php echo null ?>">Nenhuma</option>
									<option value="created">Criação</option>
									<option value="full_name">Nome</option>
									<option value="stars">Estrelas</option>
									<option value="forks">Forks</option>
									<option value="help-wanted-issues">Issues</option>
									<option value="updated">Atualizações</option>
								</select>
							</div>
							<div class="form-group col-md-2">
								<label for="per_page">Qtd por página</label>
								<input id="per_page" class="form-control" name="per_page" type="number" maxlength="100" max="100" value="30">
							</div>
							<div class="form-group col-md-2">
								<label for="page">Nº página</label>
								<input id="page" class="form-control" name="page" type="number">
							</div>
						</div>
						<button type="submit" class="btn btn-success mb-5" id="buscar">Buscar</button>
						<button type="button" style="display: none ;" disabled class="btn btn-success mb-5" id="buscar_loading"><img src="https://media.tenor.com/On7kvXhzml4AAAAj/loading-gif.gif" alt="loading"></button>
					</form>

					<hr>

					<table class="table table-sm table-striped table-hover" id="tabela">
						<thead class="thead-dark">
							<tr>
								<th scope="col">Id</th>
								<th scope="col">Nome</th>
								<th scope="col">Linguagem</th>
								<th scope="col">Caminho</th>
								<th scope="col">Descrição</th>
								<th scope="col">Visibilidade</th>
								<th scope="col">Projeto</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
				</div>

				<br><br>
			</div>
		</div>
	</div>

	<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>

	<script>
		$(document).ready(function() {
			// $('#buscar_loading').hide();
			dataTableInit();
		});


		function dataTableInit() {
			$('#tabela').DataTable();
			var opts = {
				"aaSorting": []
			};
			$('#tabela').DataTable().destroy();
			return $('#tabela').DataTable(opts);
		}

		$("#form").submit(function(e) {
			e.preventDefault();

			$('#buscar').hide();
			$('#buscar_loading').show();

			// const dados = jQuery(this).serialize();
			// console.log(dados);

			user = $('#user').val() ? '+user:' + $('#user').val() : 'thallesaguiar';
			search = $('#search').val() ? $('#search').val() : '';
			type = $('#type').val() ? $('#type').val() : 'repositories';
			language = $('#language').val() ? '+language:' + $('#language').val() : '';
			order = $('#order').val() ? $('#order').val() : '';
			sort = $('#sort').val() ? '+sort:' + $('#sort').val() : '';
			per_page = $('#per_page').val() ? $('#per_page').val() : '30';
			page = $('#page').val() ? $('#page').val() : '1';

			let queryString = `q=${search}${language}${user}${sort}`;
			// if (!search && !language && !user && !sort) {
			// 	queryString = '';
			// }

			const url = `https://api.github.com/search/${type}?${queryString}&order=${order}&page=${page}&per_page=${per_page}`;
			console.log(url);

			$.ajax({
				method: 'GET',
				url,
				dataType: 'json',
				success: function(resp) {
					tabela = dataTableInit();
					tabela.clear().draw();

					if (resp.items.length > 0) {
						resp.items.forEach(r => {
							var projeto = `<a title="Ver projeto" target='_blank' class="btn-sm btn btn-info m-1" href="${r.html_url}">Ver projeto</a>`;
							tabela.row.add([r.id, r.name, r.language, r.full_name, r.description, r.visibility, projeto]).draw(false)
						});
					}

				},
				error: function(erro) {
					console.log("Erro", erro);
					alert(erro.responseJSON.message);
				},
				complete: function(resp) {
					$('#buscar').show();
					$('#buscar_loading').hide();
				},
			})
		});

		$(document).on('keypress', function(e) {
			if (e.which == 13) {
				$('#buscar').click();
			}
		});
	</script>
</body>

</html>