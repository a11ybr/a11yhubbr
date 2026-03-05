import { useState } from "react";
import { Link } from "react-router";
import {
  ArrowLeft,
  CircleCheckBig,
  Calendar,
  Plus,
  Trash2,
} from "lucide-react";
import { Breadcrumb } from "../components/Breadcrumb";

type DateTimeSlot = {
  id: string;
  startDateTime: string;
  endDateTime: string;
};

export function SubmitEvent() {
  const [submitted, setSubmitted] = useState(false);
  const [formData, setFormData] = useState({
    modality: "",
    eventType: "",
    title: "",
    location: "",
    description: "",
    organizer: "",
    link: "",
  });
  const [dateTimeSlots, setDateTimeSlots] = useState<
    DateTimeSlot[]
  >([
    {
      id: crypto.randomUUID(),
      startDateTime: "",
      endDateTime: "",
    },
  ]);

  const modalities = [
    { value: "presencial", label: "Presencial" },
    { value: "online", label: "Online" },
    { value: "hibrido", label: "Híbrido" },
  ];

  const eventTypes = [
    { value: "workshop", label: "Workshop" },
    { value: "conferencia", label: "Conferência" },
    { value: "meetup", label: "Meetup" },
    { value: "webinar", label: "Webinar" },
    { value: "hackathon", label: "Hackathon" },
    { value: "curso", label: "Curso" },
    { value: "palestra", label: "Palestra" },
    { value: "outro", label: "Outro" },
  ];

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    // Simulate submission
    setSubmitted(true);
  };

  const handleChange = (
    e: React.ChangeEvent<
      HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement
    >,
  ) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const addDateTimeSlot = () => {
    setDateTimeSlots((prev) => [
      ...prev,
      {
        id: crypto.randomUUID(),
        startDateTime: "",
        endDateTime: "",
      },
    ]);
  };

  const removeDateTimeSlot = (id: string) => {
    setDateTimeSlots((prev) =>
      prev.filter((slot) => slot.id !== id),
    );
  };

  const updateDateTimeSlot = (
    id: string,
    field: "startDateTime" | "endDateTime",
    value: string,
  ) => {
    setDateTimeSlots((prev) =>
      prev.map((slot) =>
        slot.id === id ? { ...slot, [field]: value } : slot,
      ),
    );
  };

  if (submitted) {
    return (
      <div className="flex-1 flex items-center justify-center py-20">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
          <div className="bg-green-100 text-green-800 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
            <CircleCheckBig
              className="w-12 h-12"
              aria-hidden="true"
            />
          </div>
          <h1 className="text-3xl md:text-4xl mb-4">
            Evento submetido com sucesso!
          </h1>
          <p className="text-lg text-muted-foreground mb-8">
            Obrigado pela sua contribuição. Nossa equipe
            editorial irá revisar o evento e entraremos em
            contato em breve.
          </p>
          <div className="flex flex-col sm:flex-row gap-4 justify-center">
            <Link
              to="/submeter/eventos"
              onClick={() => setSubmitted(false)}
              className="inline-block bg-primary text-primary-foreground px-6 py-3 rounded-lg hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 text-center"
            >
              Submeter outro evento
            </Link>
            <Link
              to="/"
              className="inline-block bg-transparent border-2 border-primary text-primary px-6 py-3 rounded-lg hover:bg-primary hover:text-primary-foreground transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 text-center"
            >
              Voltar para home
            </Link>
          </div>
        </div>
      </div>
    );
  }

  return (
    <div className="flex-1">
      {/* Header */}
      <section className="bg-primary text-primary-foreground py-12 md:py-16">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <Breadcrumb
            items={[
              { label: "Submeter", href: "/submeter" },
              { label: "Eventos" },
            ]}
          />
          <div className="flex items-center gap-4 mb-6">
            <Calendar
              className="w-12 h-12"
              aria-hidden="true"
            />
            <h1 className="text-4xl md:text-5xl">
              Submeter evento
            </h1>
          </div>
          <p className="text-xl text-primary-foreground/90 max-w-2xl">
            Divulgue workshops, conferências, meetups e outros
            eventos sobre acessibilidade digital.
          </p>
        </div>
      </section>

      {/* Form Section with Sidebar */}
      <section className="py-12 md:py-16 bg-background">
        <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {/* Main Form */}
            <div className="lg:col-span-2">
              <form
                onSubmit={handleSubmit}
                className="space-y-8"
              >
                {/* Event Details */}
                <div className="border border-border rounded-lg p-6 bg-[#ffffff]">
                  <h2 className="text-2xl mb-6">
                    Detalhes do evento
                  </h2>

                  {/* Modality */}
                  <div className="mb-6">
                    <label
                      htmlFor="modality"
                      className="block mb-2"
                    >
                      Modalidade{" "}
                      <span
                        className="text-destructive"
                        aria-label="obrigatório"
                      >
                        *
                      </span>
                    </label>
                    <select
                      id="modality"
                      name="modality"
                      value={formData.modality}
                      onChange={handleChange}
                      required
                      className="w-full px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-[#ffffff]"
                      aria-required="true"
                    >
                      <option value="">
                        Selecione a modalidade
                      </option>
                      {modalities.map((mod) => (
                        <option
                          key={mod.value}
                          value={mod.value}
                        >
                          {mod.label}
                        </option>
                      ))}
                    </select>
                  </div>

                  {/* Event Type */}
                  <div className="mb-6">
                    <label
                      htmlFor="eventType"
                      className="block mb-2"
                    >
                      Tipo de evento{" "}
                      <span
                        className="text-destructive"
                        aria-label="obrigatório"
                      >
                        *
                      </span>
                    </label>
                    <select
                      id="eventType"
                      name="eventType"
                      value={formData.eventType}
                      onChange={handleChange}
                      required
                      className="w-full px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-[#ffffff]"
                      aria-required="true"
                    >
                      <option value="">
                        Selecione o tipo de evento
                      </option>
                      {eventTypes.map((type) => (
                        <option
                          key={type.value}
                          value={type.value}
                        >
                          {type.label}
                        </option>
                      ))}
                    </select>
                  </div>

                  {/* Title */}
                  <div className="mb-6">
                    <label
                      htmlFor="title"
                      className="block mb-2"
                    >
                      Título do evento{" "}
                      <span
                        className="text-destructive"
                        aria-label="obrigatório"
                      >
                        *
                      </span>
                    </label>
                    <input
                      type="text"
                      id="title"
                      name="title"
                      value={formData.title}
                      onChange={handleChange}
                      required
                      className="w-full px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-[#ffffff]"
                      placeholder="Ex: Testes de Acessibilidade"
                      aria-required="true"
                    />
                  </div>
                  <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-6">
                    {/* Date and Time Slots */}
                    <label className="block mb-2">
                      Datas e horários do evento{" "}
                      <span
                        className="text-destructive"
                        aria-label="obrigatório"
                      >
                        *
                      </span>
                    </label>
                    <div className="space-y-3">
                      {dateTimeSlots.map((slot, index) => (
                        <div
                          key={slot.id}
                          className="grid grid-cols-1 md:grid-cols-[1fr,1fr,auto] gap-3 items-end"
                        >
                          {/* Start DateTime */}
                          <div>
                            <label
                              htmlFor={`start-${slot.id}`}
                              className="block mb-2 text-sm"
                            >
                              Início{" "}
                              <span
                                className="text-destructive"
                                aria-label="obrigatório"
                              >
                                *
                              </span>
                            </label>
                            <input
                              type="datetime-local"
                              id={`start-${slot.id}`}
                              value={slot.startDateTime}
                              onChange={(e) =>
                                updateDateTimeSlot(
                                  slot.id,
                                  "startDateTime",
                                  e.target.value,
                                )
                              }
                              required
                              className="w-full px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-[#ffffff]"
                              aria-required="true"
                            />
                          </div>

                          {/* End DateTime */}
                          <div>
                            <label
                              htmlFor={`end-${slot.id}`}
                              className="block mb-2 text-sm"
                            >
                              Fim{" "}
                              <span
                                className="text-destructive"
                                aria-label="obrigatório"
                              >
                                *
                              </span>
                            </label>
                            <input
                              type="datetime-local"
                              id={`end-${slot.id}`}
                              value={slot.endDateTime}
                              onChange={(e) =>
                                updateDateTimeSlot(
                                  slot.id,
                                  "endDateTime",
                                  e.target.value,
                                )
                              }
                              required
                              className="w-full px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-[#ffffff]"
                              aria-required="true"
                            />
                          </div>

                          {/* Remove Button */}
                          {dateTimeSlots.length > 1 && (
                            <button
                              type="button"
                              onClick={() =>
                                removeDateTimeSlot(slot.id)
                              }
                              className="px-4 py-3 text-destructive hover:bg-destructive/10 rounded-lg focus:outline-none focus:ring-2 focus:ring-destructive focus:ring-offset-2 transition-colors"
                              aria-label={`Remover data e horário ${index + 1}`}
                            >
                              <Trash2
                                className="w-5 h-5"
                                aria-hidden="true"
                              />
                            </button>
                          )}
                        </div>
                      ))}

                      {/* Add Date Button */}
                      <button
                        type="button"
                        onClick={addDateTimeSlot}
                        className="text-primary hover:text-primary/80 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 rounded flex items-center gap-2 py-2"
                      >
                        <Plus
                          className="w-4 h-4"
                          aria-hidden="true"
                        />
                        Adicionar outra data
                      </button>
                    </div>
                    <p className="text-sm text-muted-foreground mt-2">
                      Para eventos com múltiplas datas, adicione
                      todas as datas e horários
                    </p>
                  </div>

                  {/* Location */}
                  <div className="mb-0">
                    <label
                      htmlFor="location"
                      className="block mb-2"
                    >
                      Localização (cidade/estado) ou ferramenta
                      de transmissão{" "}
                      <span
                        className="text-destructive"
                        aria-label="obrigatório"
                      >
                        *
                      </span>
                    </label>
                    <input
                      type="text"
                      id="location"
                      name="location"
                      value={formData.location}
                      onChange={handleChange}
                      required
                      className="w-full px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-[#ffffff]"
                      placeholder="Ex: Porto Alegre, RS ou Zoom"
                      aria-required="true"
                    />
                    <p className="text-sm text-muted-foreground mt-2">
                      Para eventos presenciais/híbridos: cidade
                      e estado. Para eventos online: plataforma
                      utilizada
                    </p>
                  </div>
                </div>

                {/* Additional Information */}
                <div className="border border-border rounded-lg p-6 bg-[#ffffff]">
                  <h2 className="text-2xl mb-6">
                    Informações adicionais
                  </h2>

                  {/* Description */}
                  <div className="mb-6">
                    <label
                      htmlFor="description"
                      className="block mb-2"
                    >
                      Descrição{" "}
                      <span
                        className="text-destructive"
                        aria-label="obrigatório"
                      >
                        *
                      </span>
                    </label>
                    <textarea
                      id="description"
                      name="description"
                      value={formData.description}
                      onChange={handleChange}
                      required
                      rows={5}
                      className="w-full px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent resize-none bg-[#ffffff]"
                      placeholder="Ex: Ferramentas e metodologias para realizar testes de acessibilidade eficientes em aplicações web."
                      aria-required="true"
                    />
                  </div>

                  {/* Organizer */}
                  <div className="mb-6">
                    <label
                      htmlFor="organizer"
                      className="block mb-2"
                    >
                      Organizador{" "}
                      <span
                        className="text-destructive"
                        aria-label="obrigatório"
                      >
                        *
                      </span>
                    </label>
                    <input
                      type="text"
                      id="organizer"
                      name="organizer"
                      value={formData.organizer}
                      onChange={handleChange}
                      required
                      className="w-full px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-[#ffffff]"
                      placeholder="Ex: QA Brasil"
                      aria-required="true"
                    />
                  </div>

                  {/* Link */}
                  <div className="mb-0">
                    <label
                      htmlFor="link"
                      className="block mb-2"
                    >
                      Link do evento{" "}
                      <span
                        className="text-destructive"
                        aria-label="obrigatório"
                      >
                        *
                      </span>
                    </label>
                    <input
                      type="url"
                      id="link"
                      name="link"
                      value={formData.link}
                      onChange={handleChange}
                      required
                      className="w-full px-4 py-3 bg-input-background border border-border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent bg-[#ffffff]"
                      placeholder="https://"
                      aria-required="true"
                    />
                    <p className="text-sm text-muted-foreground mt-2">
                      Link para inscrição ou página oficial do
                      evento
                    </p>
                  </div>
                </div>

                {/* Submit Button */}
                <div className="pt-4">
                  <button
                    type="submit"
                    className="bg-primary text-primary-foreground px-8 py-4 rounded-lg hover:bg-primary/90 transition-colors focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2"
                  >
                    Enviar para revisão
                  </button>
                </div>
              </form>
            </div>

            {/* Sidebar */}
            <div className="lg:col-span-1 space-y-6">
              {/* Event Guidelines */}
              <div className="bg-accent border border-border rounded-lg p-6 bg-[#ffffff]">
                <h2 className="text-xl mb-4">
                  Diretrizes para eventos
                </h2>
                <ul className="space-y-2 text-muted-foreground">
                  <li className="flex items-start gap-2">
                    • Eventos devem ter foco em acessibilidade
                    digital
                  </li>
                  <li className="flex items-start gap-2">
                    • Forneça informações completas e precisas
                  </li>
                  <li className="flex items-start gap-2">
                    • Indique claramente a modalidade
                    (presencial/online/híbrido)
                  </li>
                  <li className="flex items-start gap-2">
                    • Inclua link oficial para mais informações
                  </li>
                  <li className="flex items-start gap-2">
                    • Mencione se há requisitos de
                    acessibilidade
                  </li>
                </ul>
              </div>

              {/* Review Process */}
              <div className="bg-primary border text-primary-foreground border-border rounded-lg p-6">
                <h2 className="text-xl mb-4">
                  Processo de revisão
                </h2>
                <ol className="space-y-3">
                  <li className="flex gap-3">
                    1. Submissão recebida
                  </li>
                  <li className="flex gap-3">
                    2. Análise editorial (até 3 dias úteis)
                  </li>
                  <li className="flex gap-3">
                    3. Feedback por e-mail
                  </li>
                  <li className="flex gap-3">
                    4. Publicação após aprovação
                  </li>
                </ol>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  );
}
