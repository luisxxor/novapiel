<link rel="stylesheet" href="<?PHP echo base_url('assets/bulma/bulma-tooltip.min.css'); ?>">
<link rel="stylesheet" href="<?PHP echo base_url('assets/animate/animate.css'); ?>">
<link rel="stylesheet" href="https://unpkg.com/buefy/dist/buefy.min.css">

<style>
  #pageTitle {
    margin-top: 1em;
  }

  .clickable {
    cursor: pointer;
  }

  tr.is-readed {
    color: #565554;
    background-color: lightgray;
  }

  #modalHeader {
    display: flex;
    justify-content: space-around;
  }

  #messageBody {
    padding: 1em 2em;
    margin: 0;
  }

  #markAsUnreadButton {
    margin-left: 2em;
    margin-top: 2em;
  }

</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vee-validate@latest/dist/vee-validate.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@7.33.1/dist/sweetalert2.all.min.js" integrity="sha256-Qfxgn9jULeGAdbaeDjXeIhZB3Ra6NCK3dvjwAG8Y+xU=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.24.0/moment.min.js"></script>

<div id="app">
  <section>
    <div class="container">
      <h2 id="pageTitle" class="title is-2 has-text-centered">Listado de mensajes de contacto</h2>

      <div v-cloak class="card">
        <div v-cloak class="card-content">
          <div v-cloak class="content">
            <div class="field">
              <b-switch v-model="showReadedMessages">
                {{ showReadedMessages ? 'Todos los mensajes' : 'Solo mensajes no leídos' }}
              </b-switch>
            </div>
            <b-table
              :data="messagesList"
              :columns="columns"
              narrowed
              hoverable
              :row-class="(row, index) => row.leido && 'is-readed'"
            >
              <template slot-scope="props">
                <b-table-column field="nombre" label="Nombre" sortable>
                  {{ props.row.nombre }}
                </b-table-column>

                <b-table-column field="email" label="Email" sortable>
                  {{ props.row.email }}
                </b-table-column>

                <b-table-column field="telefono" label="Telefono" sortable>
                  {{ props.row.telefono }}
                </b-table-column>

                <b-table-column field="fecha" label="Fecha" sortable>
                  {{ props.row.fecha | date }}
                </b-table-column>

                <b-table-column label="Acciones">
                  <button class="button is-small" @click.prevent="openMessage(props.row.id)">
                    <b-icon pack="fa" icon="eye" ></b-icon>
                  </button>
                </b-table-column>
              </template>

              <template slot="empty">
                <section class="section">
                    <div class="content has-text-grey has-text-centered">
                        <p>Nada que mostrar aquí.</p>
                    </div>
                </section>
            </template>
            </b-table>    
            
            <b-modal :active.sync="messageModal" :width="640" scroll="keep">
              <div class="card">
                <div class="card-content">
                  <div class="content">
                    
                    <div id="modalHeader">                    
                      <span class="is-size-4 has-text-grey">{{ selected.nombre }}</span>
                      <span class="is-size-4 has-text-grey">{{ selected.fecha | date }}</span>
                    </div>

                    <button id="markAsUnreadButton" class="button is-small" @click.prevent="markAsUnread(selected.id)">
                      Marcar como no leído
                    </button>

                    <p id="messageBody">{{ selected.mensaje }}</p>

                  </div>
                </div>
            </b-modal>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>


<script src="https://unpkg.com/buefy/dist/buefy.min.js"></script>
<script src="https://unpkg.com/buefy/dist/components/table"></script>

<script>
  axios.defaults.baseURL = '<?PHP echo base_url(); ?>';
  
  new Vue({
    el: '#app',
    data: {
      contactMessages: [],
      columns: [
        {
          field: 'nombre',
          label: 'Nombre'
        },
        {
          field: 'email',
          label: 'Email',
        },
        {
          field: 'telefono',
          label: 'Telefono',
          centered: true
        },
        {
          field: 'mensaje',
          label: 'Mensaje',
          centered: true
        },
        {
          field: 'fecha',
          label: 'Fecha',
          centered: true
        }
      ],
      messageModal: false,
      selected: {
        id: null,
        mensaje: '',
        nombre: '',
        fecha: '',
        email: '',
        telefono: '',
        leido: false
      },
      showReadedMessages: true
    },
    methods: {
      loadContactMessages() {
        axios.get('contacto/getAll')
        .then(({data: {contactMessages}}) => {
          contactMessages.forEach(v => v.leido = v.leido === "1")
          this.contactMessages = contactMessages
        })
      },
      openMessage(id) {
        this.messageModal = true;
        this.selected = this.contactMessages.find(v => v.id == id);

        if(!this.selected.leido)
        {        
          let formdata = new FormData();
          formdata.append('id', id);
          axios.post('contacto/markAsRead',formdata)
            .then( response => {
              this.loadContactMessages()
            })
        }
      },
      markAsUnread(id) {
        let formdata = new FormData();
        formdata.append('id', id);
        axios.post('contacto/markAsUnread', formdata)
        .then( response => {
          this.loadContactMessages()
          this.messageModal = false
        })
      }
    },
    computed: {
      contactMessagesAreEmpty() {
        return this.contactMessages.length == 0
      },
      messagesList() {
        return this.showReadedMessages ? this.contactMessages : this.contactMessages.filter(v => v.leido === false)
      },

    },
    filters: {
      date(val) {
        return moment(val).format('DD/MM/YYYY')
      }
    },
    created() {
      this.loadContactMessages();
    }
  })
</script>
