<div>
    <form method="POST" action="{{ route('register.storeUserAndChurch', ['ministry' => $this->ministry, 'event' => $this->event, 'token' => $this->token]) }}" class="space-y-6">
        @csrf
        <div>
            <flux:heading size="lg">Kirche eintragen</flux:heading>
            <flux:text class="mt-2">Bitte gib die folgenden Informationen an</flux:text>
        </div>
        <input type="hidden" name="event_id" value="{{ $this->event->id }}">
        <flux:field>
            <flux:label>Vorname</flux:label>
            <flux:input name="first_name" type="text" :value="old('first_name')"/>
            <flux:error name="first_name" />
        </flux:field>
        <flux:field>
            <flux:label>Nachname</flux:label>
            <flux:input name="last_name" type="text" :value="old('last_name')"/>
            <flux:error name="last_name" />
        </flux:field>
        <flux:field>
            <flux:label>E-Mail</flux:label>
            <flux:input name="email" type="email" :value="old('email')"/>
            <flux:error name="email" />
        </flux:field>
        <flux:field>
            <flux:label>Telefon</flux:label>
            <flux:input name="phone" type="text" :value="old('phone')"/>
            <flux:error name="phone" />
        </flux:field>
        <flux:field>
            <flux:label>Name Kirche</flux:label>
            <flux:input name="church_name" type="text" :value="old('church_name')"/>
            <flux:error name="church_name" />
        </flux:field>
        <flux:field>
            <flux:label>Funktion</flux:label>
            <flux:select name="role" variant="listbox" placeholder="Wähle die Funktion" value="{{ $this->role }}">
                <flux:select.option value="pastor">Pastor</flux:select.option>
                <flux:select.option value="ambassador">Botschafter</flux:select.option>
                <flux:select.option value="church_member">Mitarbeiter</flux:select.option>
            </flux:select>
            <flux:error name="role" />
        </flux:field>
        <div>{{ old('role') }}</div>
        <!-- Password -->
        <flux:field>
            <flux:label>Passwort</flux:label>
            <flux:input placeholder="Password" autocomplete="new-password" name="password" type="password"
                viewable :value="old('password')" />
            <flux:error name="password" />
        </flux:field>

        <!-- Confirm Password -->
        <flux:field>
            <flux:label>Passwort bestätigen</flux:label>
            <flux:input name="password_confirmation" placeholder="Confirm password" autocomplete="new-password" type="password" viewable />
            <flux:error name="password_confirmation" />
        </flux:field>

        <div class="flex">
            <flux:spacer />
            <flux:button type="submit" variant="primary">Kirche registrieren
            </flux:button>
        </div>
    </form>
</div>
