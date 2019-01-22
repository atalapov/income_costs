<template>
	<v-container grid-list-md text-xs-center>
		<v-layout row wrap>
			<v-flex xs6>
				<v-toolbar flat color="white">
					<v-toolbar-title>Курс валют:</v-toolbar-title>
					<v-divider
					class="mx-2"
					inset
					vertical
					></v-divider>
					<v-toolbar-title>USD: {{usdbuy}} / {{usdsale}}</v-toolbar-title>
					<v-toolbar-title>RUR: {{rurbuy}} / {{rursale}}</v-toolbar-title>
				</v-toolbar>
			</v-flex>
			<v-flex xs6>
				<v-toolbar flat color="white">
					<v-toolbar-title>Список кошельков:</v-toolbar-title>
					<v-divider
					class="mx-2"
					inset
					vertical
					></v-divider>					
					<v-spacer></v-spacer>
					<v-dialog v-model="dialogcash" persistent max-width="500px">
						<v-btn slot="activator" color="primary" dark class="mb-2">Добавить кошелек</v-btn>
						<v-card>
							<v-toolbar dark color="primary">
								<v-btn icon dark @click="dialogcash = false">
									<v-icon>close</v-icon>
								</v-btn>
							</v-toolbar>
							<add-cash></add-cash>
						</v-card>
					</v-dialog>
				</v-toolbar>
			</v-flex>
			<v-flex xs12>
				<div>
					<v-toolbar flat color="white">
						<v-toolbar-title>Список операций</v-toolbar-title>
						<v-divider
						class="mx-2"
						inset
						vertical
						></v-divider>
						<v-spacer></v-spacer>
						<v-text-field
						v-model="search"
						append-icon="search"
						label="Поиск"
						single-line
						hide-details
						></v-text-field>
						<v-spacer></v-spacer>
						<v-dialog v-model="dialog" persistent fullscreen>
							<v-btn slot="activator" color="primary" dark class="mb-2">Добавить операцию</v-btn>
							<v-card>
								<v-toolbar dark color="primary">
									<v-btn icon dark @click="dialog = false">
										<v-icon>close</v-icon>
									</v-btn>
								</v-toolbar>
								<add-operation></add-operation>
							</v-card>
						</v-dialog>
					</v-toolbar>
					<v-data-table
					:headers="headers"
					:items="operations"
					:search="search"
					:rows-per-page-items="rowsPerPageItems"
					:rows-per-page-text="rowsPerPageText"					
					class="maintable"
					>
					<template slot="items" slot-scope="props">
						<td class="text-xs-left">{{ props.item.title }}</td>
						<td class="text-xs-left">{{ props.item.type }}</td>
						<td class="text-xs-left">{{ props.item.category }}</td>
						<td class="text-xs-left">{{ props.item.date }}</td>
						<td class="text-xs-left">{{ props.item.sum }}</td>
						<td class="text-xs-left">{{ props.item.ccy }}</td>
						<td class="justify-center layout px-0">
							<v-icon
							small
							class="mr-2"
							@click="editItem(props.item)"
							>
							edit
						</v-icon>
						<v-icon
						small
						@click="deleteItem(props.item)"
						>
						delete
					</v-icon>
				</td>
			</template>
			<template slot="pageText" slot-scope="item">
				{{item.pageStart}} - {{item.pageStop}} из {{item.itemsLength}}
			</template>
			<template slot="no-data">
				<v-btn color="primary" @click="initialize">Reset</v-btn>
			</template>
		</v-data-table>
	</div>
</v-flex>
</v-layout>
</v-container>
</template>

<script>
	export default {
		data: () => ({
			usdbuy:'',
			usdsale:'',
			rurbuy:'',
			rursale:'',
			currencies:{},	
			currencieslist:new Array(),
			search: '',
			dialog: false,
			dialogcash: false,
			rowsPerPageItems: [5, 10],
			rowsPerPageText: 'Отображать по:',
			headers: [
			{ text: 'Название операции', value: 'title'},
			{ text: 'Тип операции', value: 'type' },
			{ text: 'Категория', value: 'category' },
			{ text: 'Дата добавления', value: 'date' },
			{ text: 'Сумма', value: 'sum' },
			{ text: 'Валюта', value: 'ccy'}
			],
			operations: [],
			editedIndex: -1,
			editedItem: {
				name: '',
				calories: 0,
				fat: 0,
				carbs: 0,
				protein: 0
			},
			defaultItem: {
				name: '',
				calories: 0,
				fat: 0,
				carbs: 0,
				protein: 0
			}
		}),
		mounted: function() {
			var self = this;
			axios.get('https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5')
			.then(response => {
				for (var k in response.data) {
					var smdata = response.data[k];				
					self.currencies[smdata.ccy] = {};
					self.currencies[smdata.ccy].ccy  = smdata.ccy;
					self.currencies[smdata.ccy].buy  = smdata.buy;
					self.currencies[smdata.ccy].sale = smdata.sale;
					var currencies = this.currencies;
					self.currencieslist.push(this.currencies[smdata.ccy]);
				}
				self.usdbuy = self.currencies.USD.buy;
				self.usdsale = self.currencies.USD.sale;	
				self.rurbuy = self.currencies.RUR.buy;
				self.rursale = self.currencies.RUR.sale;

				self.usdbuy = parseFloat(self.usdbuy).toFixed(2);
				self.usdsale = parseFloat(self.usdsale).toFixed(2);
				self.rurbuy = parseFloat(self.rurbuy).toFixed(2);
				self.rursale = parseFloat(self.rursale).toFixed(2);
			})
		},
		computed: {
			formTitle () {
				return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
			}			
		},

		watch: {
			dialog (val) {
				val || this.close()
			},
			dialogcash (val) {
				val || this.close()
			}
		},

		created () {
			this.initialize()
		},

		methods: {
			initialize () {
				axios.get('http://vue/backend/income_costs/list').then(response => {
					this.operations = response.data;					
				});
			},
			editItem (item) {
				this.editedIndex = this.desserts.indexOf(item)
				this.editedItem = Object.assign({}, item)
				this.dialog = true
			},

			deleteItem (item) {
				const index = this.desserts.indexOf(item)
				confirm('Are you sure you want to delete this item?') && this.desserts.splice(index, 1)
			},

			close () {
				this.dialog = false
				this.dialogcash = false
				setTimeout(() => {
					this.editedItem = Object.assign({}, this.defaultItem)
					this.editedIndex = -1
				}, 300)
			},

			save () {
				if (this.editedIndex > -1) {
					Object.assign(this.desserts[this.editedIndex], this.editedItem)
				} else {
					this.desserts.push(this.editedItem)
				}
				this.close()
			}

		}
	}
</script>

<style lang="css" scoped>
.maintable >>> .v-table th{
	font-size: 16px
}
.maintable .v-table td {
	font-size: 14px;
}

</style>
