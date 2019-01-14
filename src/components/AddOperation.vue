<template>
	<v-container grid-list-md text-xs-center>
		<v-layout row wrap>
			<v-flex xs12 sm6 offset-sm3>
				<v-card color="primary">
					<v-flex xs12 md10 offset-md1>
						<h1 class="font-weight-light">Нужно заполнить форму для добавления операции</h1>
						<v-form class="operationform">
							<h2 class="font-weight-light toleft">Выберите тип операции:</h2>
							<v-radio-group v-model="selected">
								<template v-for="operationitem in operationitems">
									<v-radio 
									class="title"
									color="orange"
									:label="operationitem.name" 
									:name="operationitem.value"
									:value="operationitem.value"
									></v-radio>
								</template>									
							</v-radio-group>	
							<div v-if="checkNote() == 'income'">
								<h2 class="font-weight-light toleft">Выберите категорию доходов:</h2>
								<v-radio-group v-model="selectedcatin">
									<template v-for="operationcatin in operationcatins">
										<v-radio 
										class="title"
										color="orange"
										:label="operationcatin.name" 
										:name="operationcatin.value"
										:value="operationcatin.value"
										></v-radio>
									</template>
								</v-radio-group>
							</div>
							<div v-if="checkNote() == 'costs'">
								<h2 class="font-weight-light toleft">Выберите категорию расходов:</h2>
								<v-radio-group v-model="selectedcatcost">
									<template v-for="operationcatcost in operationcatcosts">
										<v-radio 
										class="title"
										color="orange"
										:label="operationcatcost.name" 
										:name="operationcatcost.value"
										:value="operationcatcost.value"
										></v-radio>
									</template>
								</v-radio-group>
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
		name: 'AddPayment',
		data: function() {
			return {
				selected: '',
				selectedcatin: '',
				selectedcatcost: '',
				operationitems : [],
				operationcatins : [],
				operationcatcosts : []
			};
		},
		created() {
			axios.get(`http://vue/data.json`)
			.then(response => {
			    // JSON responses are automatically parsed.
			    this.posts = response.data })
			.catch(e => {
				this.errors.push(e)
			})
		},
		mounted: function() {
			var self = this;
			setTimeout(function(self) {			
				self.operationitems = [{
					name: 'Доходы',
					value: 'income'
				}, {
					name: 'Расходы',
					value: 'costs'
				}, ];
				self.operationcatins = [{
					name: 'Зарплата',
					value: 'salary'
				}, {
					name: 'Халтура',
					value: 'quickmoney'
				}, {
					name: 'Подарок',
					value: 'gift'
				}];
				self.operationcatcosts = [{
					name: 'Покупка продуктов',
					value: 'products'
				}, {
					name: 'Транспортные расходы',
					value: 'transport'
				}, {
					name: 'Подарки',
					value: 'giftcost'
				}];
			}, 0, self);
		},
		methods: {
			checkNote: function() {			
				return this.selected;
			}
		}
	}
</script>

<style lang="css" scoped>
h2 {
	font-size: 20px;
}
.operationform {
	margin-top: 20px;
}
.toleft {
	text-align: left;
}
</style>
