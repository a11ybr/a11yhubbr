import { Calendar, MapPin, ExternalLink, Clock, Users, ArrowUpDown, Grid3x3, Monitor, Video, Merge } from "lucide-react";
import { useState, useEffect } from "react";
import { useSearchParams } from "react-router";
import { Breadcrumb } from "../components/Breadcrumb";
import { FilterControls } from "../components/FilterControls";

type SortOption = "date-new" | "date-old" | "title-az" | "title-za";
type ItemsPerPage = 8 | 16 | 24 | 32 | 40 | "all";

interface Event {
  id: number;
  title: string;
  date: string;
  time: string;
  location: string;
  type: "Presencial" | "Online" | "Híbrido";
  description: string;
  organizer: string;
  link: string;
  attendees?: number;
}

export function Events() {
  const [searchParams, setSearchParams] = useSearchParams();
  const [activeFilter, setActiveFilter] = useState<string | null>(null);
  const [sortBy, setSortBy] = useState<SortOption>("date-new");
  const [itemsToShow, setItemsToShow] = useState<ItemsPerPage>(8);

  // Initialize filter from URL on mount
  useEffect(() => {
    const filterFromUrl = searchParams.get("tipo");
    if (filterFromUrl) {
      setActiveFilter(filterFromUrl);
    }
  }, [searchParams]);

  const eventTypes = [
    { type: "Presencial", icon: MapPin, count: 3 },
    { type: "Online", icon: Video, count: 3 },
    { type: "Híbrido", icon: Merge, count: 2 },
  ];

  const handleFilterClick = (filterType: string) => {
    if (activeFilter === filterType) {
      // Se clicar no filtro ativo, remove o filtro
      setActiveFilter(null);
      setSearchParams({});
    } else {
      // Ativa o novo filtro
      setActiveFilter(filterType);
      setSearchParams({ tipo: filterType });
    }
  };

  // Mock data de eventos
  const sampleEvents: Event[] = [
    {
      id: 1,
      title: "Workshop: WCAG 2.1 na Prática",
      date: "2026-03-15",
      time: "14:00",
      location: "São Paulo, SP",
      type: "Presencial",
      description: "Aprenda a implementar as diretrizes WCAG 2.1 em projetos reais com exemplos práticos e estudos de caso.",
      organizer: "Instituto de Acessibilidade Digital",
      link: "https://example.com/evento1",
      attendees: 45,
    },
    {
      id: 2,
      title: "Webinar: Acessibilidade em React",
      date: "2026-03-20",
      time: "19:00",
      location: "Online",
      type: "Online",
      description: "Técnicas e bibliotecas para desenvolver aplicações React acessíveis, com foco em componentes e navegação por teclado.",
      organizer: "React Brasil",
      link: "https://example.com/evento2",
      attendees: 120,
    },
    {
      id: 3,
      title: "Conferência A11Y Brasil 2026",
      date: "2026-04-10",
      time: "09:00",
      location: "Rio de Janeiro, RJ",
      type: "Híbrido",
      description: "A maior conferência de acessibilidade digital do Brasil, com palestrantes nacionais e internacionais.",
      organizer: "A11Y Brasil",
      link: "https://example.com/evento3",
      attendees: 300,
    },
    {
      id: 4,
      title: "Meetup: Acessibilidade Mobile",
      date: "2026-03-25",
      time: "18:30",
      location: "Belo Horizonte, MG",
      type: "Presencial",
      description: "Discussão sobre boas práticas de acessibilidade em aplicativos mobile iOS e Android.",
      organizer: "Mobile Dev BH",
      link: "https://example.com/evento4",
      attendees: 60,
    },
    {
      id: 5,
      title: "Curso: Design Inclusivo para UX/UI",
      date: "2026-04-05",
      time: "10:00",
      location: "Online",
      type: "Online",
      description: "Curso completo sobre princípios de design inclusivo e como aplicá-los em interfaces digitais.",
      organizer: "UX Brasil",
      link: "https://example.com/evento5",
      attendees: 85,
    },
    {
      id: 6,
      title: "Hackathon: Tecnologia Assistiva",
      date: "2026-04-15",
      time: "08:00",
      location: "Curitiba, PR",
      type: "Presencial",
      description: "Desenvolva soluções inovadoras de tecnologia assistiva em um fim de semana colaborativo.",
      organizer: "Tech For Good",
      link: "https://example.com/evento6",
      attendees: 40,
    },
    {
      id: 7,
      title: "Palestra: Libras e Tecnologia",
      date: "2026-03-30",
      time: "16:00",
      location: "Online",
      type: "Online",
      description: "Como a tecnologia pode auxiliar na comunicação em Libras e inclusão da comunidade surda.",
      organizer: "Incluir Digital",
      link: "https://example.com/evento7",
      attendees: 95,
    },
    {
      id: 8,
      title: "Workshop: Testes de Acessibilidade",
      date: "2026-04-20",
      time: "13:00",
      location: "Porto Alegre, RS",
      type: "Híbrido",
      description: "Ferramentas e metodologias para realizar testes de acessibilidade eficientes em aplicações web.",
      organizer: "QA Brasil",
      link: "https://example.com/evento8",
      attendees: 70,
    },
  ];

  // Filtragem
  const filteredEvents = activeFilter
    ? sampleEvents.filter((event) => event.type === activeFilter)
    : sampleEvents;

  // Ordenação
  const sortedEvents = [...filteredEvents].sort((a, b) => {
    switch (sortBy) {
      case "date-new":
        return new Date(b.date).getTime() - new Date(a.date).getTime();
      case "date-old":
        return new Date(a.date).getTime() - new Date(b.date).getTime();
      case "title-az":
        return a.title.localeCompare(b.title);
      case "title-za":
        return b.title.localeCompare(a.title);
      default:
        return 0;
    }
  });

  // Paginação
  const eventsToDisplay =
    itemsToShow === "all" ? sortedEvents : sortedEvents.slice(0, itemsToShow);

  const formatDate = (dateString: string) => {
    const date = new Date(dateString);
    return date.toLocaleDateString("pt-BR", {
      day: "2-digit",
      month: "long",
      year: "numeric",
    });
  };

  const getTypeColor = (type: Event["type"]) => {
    switch (type) {
      case "Online":
        return "bg-blue-100 text-blue-800";
      case "Presencial":
        return "bg-green-100 text-green-800";
      case "Híbrido":
        return "bg-purple-100 text-purple-800";
    }
  };

  return (
    <div className="flex-1">
      {/* Header */}
      <section className="bg-primary text-primary-foreground py-12 md:py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <Breadcrumb items={[{ label: "Eventos" }]} />
          <div className="flex items-center gap-4 mb-6">
            <Calendar className="w-12 h-12" aria-hidden="true" />
            <h1 className="text-4xl md:text-5xl">Eventos</h1>
          </div>
          <p className="text-xl text-primary-foreground/90 max-w-2xl">
            Participe de workshops, conferências e meetups sobre acessibilidade
            digital no Brasil.
          </p>
        </div>
      </section>

      {/* Filtros por tipo de evento */}
      <FilterControls
        title="Navegue por tipo"
        filters={eventTypes}
        activeFilter={activeFilter}
        onFilterChange={handleFilterClick}
        itemLabel="eventos"
      />

      {/* Controles de ordenação e visualização */}
      <section className="py-8 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex flex-col lg:flex-row gap-4 justify-between items-start lg:items-center">
          {/* Título */}
          <h2 className="text-2xl md:text-3xl">
            {activeFilter
              ? `Eventos: ${activeFilter}`
              : "Todos os eventos"}
          </h2>

          {/* Controles */}
          <div className="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
            {/* Ordenação */}
            <div className="flex items-center gap-3">
              <ArrowUpDown className="w-5 h-5 text-muted-foreground" aria-hidden="true" />
              <label htmlFor="sort-events" className="text-sm font-medium">
                Ordenar por:
              </label>
              <select
                id="sort-events"
                value={sortBy}
                onChange={(e) => setSortBy(e.target.value as SortOption)}
                className="border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary bg-background"
              >
                <option value="date-new">Data (mais recentes)</option>
                <option value="date-old">Data (mais antigos)</option>
                <option value="title-az">Título (A-Z)</option>
                <option value="title-za">Título (Z-A)</option>
              </select>
            </div>

            {/* Itens por página */}
            <div className="flex items-center gap-3">
              <Grid3x3 className="w-5 h-5 text-muted-foreground" aria-hidden="true" />
              <label htmlFor="items-per-page" className="text-sm font-medium">
                Exibir:
              </label>
              <select
                id="items-per-page"
                value={itemsToShow}
                onChange={(e) =>
                  setItemsToShow(
                    e.target.value === "all" ? "all" : (Number(e.target.value) as ItemsPerPage)
                  )
                }
                className="border border-border rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary bg-background"
              >
                <option value={8}>8 eventos</option>
                <option value={16}>16 eventos</option>
                <option value={24}>24 eventos</option>
                <option value={32}>32 eventos</option>
                <option value={40}>40 eventos</option>
                <option value="all">Todos os eventos</option>
              </select>
            </div>
          </div>
        </div>
      </section>

      {/* Lista de Eventos */}
      <section className="py-12 md:py-16" aria-label="Lista de eventos">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

          <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
            {eventsToDisplay.map((event) => (
              <article
                key={event.id}
                className="bg-card border border-border rounded-lg p-6 hover:shadow-lg transition-shadow focus-within:ring-2 focus-within:ring-primary"
              >
                {/* Tipo do evento */}
                <div className="flex items-center justify-between mb-4">
                  <span
                    className={`text-xs font-semibold px-3 py-1 rounded-full ${getTypeColor(
                      event.type
                    )}`}
                  >
                    {event.type}
                  </span>
                  {/* {event.attendees && (
                    <div className="flex items-center gap-1 text-sm text-muted-foreground">
                      <Users className="w-4 h-4" aria-hidden="true" />
                      <span>{event.attendees} participantes</span>
                    </div>
                  )} */}
                </div>

                {/* Título */}
                <h2 className="text-xl md:text-2xl mb-3">
                  <a
                  href={event.link}
                  target="_blank"
                  rel="noopener noreferrer"
                  className="text-primary hover:underline focus:outline-none focus:ring-2 focus:ring-primary rounded"
                >
                  {event.title}
                  <ExternalLink className="w-4 h-4 inline-block align-text-bottom ml-1" aria-hidden="true" />
                </a>
                  
                 </h2>

                {/* Data, hora e local */}
                <div className="space-y-2 mb-4 text-sm text-muted-foreground">
                  
                  <div className="flex items-center justify-between gap-4">
                    <div className="flex items-center gap-2">
                      <Calendar className="w-4 h-4" aria-hidden="true" />
                      <span>{new Date(event.date).toLocaleDateString("pt-BR", { day: "2-digit", month: "2-digit", year: "numeric" })}</span>
                    </div>
                    <div className="flex items-center gap-2">
                      <Clock className="w-4 h-4" aria-hidden="true" />
                      <span>{event.time}</span>
                    </div>
                  </div>
                  <div className="flex items-center gap-2">
                    <MapPin className="w-4 h-4" aria-hidden="true" />
                    <span>{event.location}</span>
                  </div>
                </div>

                {/* Descrição */}
                <p className="text-muted-foreground mb-4">{event.description}</p>

                {/* Organizador */}
                <p className="text-sm text-muted-foreground mb-4">
                  <span className="font-semibold">Organizado por:</span> {event.organizer}
                </p>

                {/* Link */}
                
              </article>
            ))}
          </div>

          {eventsToDisplay.length === 0 && (
            <div className="text-center py-12">
              <Calendar className="w-16 h-16 mx-auto mb-4 text-muted-foreground" aria-hidden="true" />
              <p className="text-xl text-muted-foreground">
                Nenhum evento encontrado.
              </p>
            </div>
          )}
        </div>
      </section>
    </div>
  );
}
