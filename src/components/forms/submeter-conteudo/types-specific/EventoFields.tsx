import { FormState } from "../types";

type Props = {
  form: FormState;
  onChange: (field: keyof FormState, value: any) => void;
};

export function EventoFields({ form, onChange }: Props) {
  return (
    <fieldset className="space-y-4">
      <legend className="block text-lg font-medium">Destaques do Evento</legend>

      <select
        className="input-base"
        value={form.tipoEvento || ""}
        onChange={(e) => onChange("tipoEvento", e.target.value)}
        required
      >
        <option value="">Tipo de evento</option>
        <option value="online">Online</option>
        <option value="presencial">Presencial</option>
        <option value="hibrido">Híbrido</option>
      </select>

      <select
        className="input-base"
        value={form.categoriaEvento || ""}
        onChange={(e) => onChange("categoriaEvento", e.target.value)}
        required
      >
        <option value="">Categoria</option>
        <option value="conferencia">Conferência</option>
        <option value="workshop">Workshop</option>
        <option value="webinar">Webinar</option>
        <option value="meetup">Meetup</option>
      </select>
    </fieldset>
  );
}