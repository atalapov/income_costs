<template>
	<v-container grid-list-md text-xs-center>
		<v-layout row wrap>
			<v-flex xs12>
				<h2 class="font-weight-light mb-20">Нужно заполнить форму для добавления кошелька</h2>
				<v-card>
					<v-flex xs12 md10 offset-md1>					
						<v-form class="safeform">	
								<h2 class="font-weight-light toleft">Выберите дату:</h2>
								<h2 class="font-weight-light toleft">Напишите название:</h2>
								<v-text-field placeholder="Название элемента" name="elementname" v-model="title"></v-text-field>	
								<h2 class="font-weight-light toleft">Выберите валюту:</h2>
								<v-select :items="incomecurrency" item-text="type" item-value="value" v-model="defaultcurrency" >	
								</v-select>
								<h2 class="font-weight-light toleft">Сумма:</h2>
								<v-text-field placeholder="Введите сумму" name="summary" v-model="sum"></v-text-field>	
								<div>
									<v-btn color="warning" @click="submit">Отправить</v-btn>
								</div>
						</v-form>
					</v-flex>
				</v-card>
			</v-flex>
		</v-layout>
	</v-container>
</template>

<script>	
	export default {
		data: function() {	
			return {
				title: '',
				sum:'',			
			};
		},
		submit () {
			var datasend = {};
			var validate = true;
			datasend.type     = this.type;
			datasend.title    = this.title;
			datasend.date     = this.date;
			datasend.category = this.selectedcat;
			datasend.sum = this.sum;
			datasend.ccy = this.defaultcurrency;

			if(validate === true){
				axios.get('http://vue/backend/income_costs/add', {
					params:datasend
				})
				.then(response => {		
					console.log(response.data);	
				})
			}
		},
	}
</script>

<style lang="css" scoped>

</style>
